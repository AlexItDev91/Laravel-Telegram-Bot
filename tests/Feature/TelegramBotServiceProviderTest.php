<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramStartPayloadSigner;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotRateLimitException;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramBotApiRequestRecorded;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotLaravelConfig;
use AlexItDev91\LaravelTelegramBot\MiniApps\TelegramMiniAppInitDataValidator;
use AlexItDev91\LaravelTelegramBot\TelegramBot as TelegramBotService;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Psr\Http\Message\RequestInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

class TelegramBotServiceProviderTest extends TestCase
{
    public function test_registers_manager_client_and_facade(): void
    {
        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');

        $this->assertInstanceOf(TelegramBotManager::class, app('telegram-bot'));
        $this->assertInstanceOf(TelegramBotService::class, app(TelegramBotService::class));
        $this->assertInstanceOf(TelegramBotManager::class, app(TelegramBotManager::class));
        $this->assertInstanceOf(TelegramBotManagerContract::class, app(TelegramBotManagerContract::class));
        $this->assertInstanceOf(TelegramBotManager::class, TelegramBot::getFacadeRoot());
        $this->assertInstanceOf(TelegramBotClientContract::class, app(TelegramBotClientContract::class));
        $this->assertInstanceOf(TelegramBotClient::class, app(TelegramBotClient::class));
        $this->assertInstanceOf(TelegramBotLaravelConfig::class, app(TelegramBotLaravelConfig::class));
    }

    public function test_supports_constructor_injection_for_laravel_di(): void
    {
        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');

        $consumer = app(TelegramBotDiConsumer::class);

        $this->assertSame(app(TelegramBotService::class), $consumer->telegram);
        $this->assertSame(app('telegram-bot'), $consumer->manager);
        $this->assertSame(app('telegram-bot'), $consumer->managerContract);
        $this->assertSame(app('telegram-bot')->bot(), $consumer->telegram->bot());
        $this->assertTrue(method_exists($consumer->telegram, 'sendMessage'));
        $this->assertSame(app(TelegramBotClientContract::class), $consumer->clientContract);
        $this->assertSame(app(TelegramBotClient::class), $consumer->client);
        $this->assertSame(app(TelegramMiniAppInitDataValidator::class), $consumer->miniAppValidator);
        $this->assertSame(app(TelegramStartPayloadSigner::class), $consumer->startPayloadSigner);
    }

    public function test_uses_container_bound_http_client_for_laravel_di(): void
    {
        $history = [];

        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');

        $this->app->bind(ClientInterface::class, function () use (&$history): ClientInterface {
            return $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => ['id' => 1]], JSON_THROW_ON_ERROR)),
            ], $history);
        });

        app(TelegramBotClient::class)->getMe();

        $this->assertSame('/bot123456:test-token/getMe', $history[0]['request']->getUri()->getPath());
    }

    public function test_facade_and_di_support_dynamic_bot_tokens_and_destinations(): void
    {
        $history = [];

        config()->set('telegram-bot.api_url', 'https://api.telegram.test');

        $this->app->bind(ClientInterface::class, function () use (&$history): ClientInterface {
            return $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ], $history);
        });

        TelegramBot::to('-1001234567890', token: '222:facade-token')->sendMessage([
            'text' => 'Facade dynamic send',
        ]);

        app(TelegramBotService::class)->to('-1009876543210', token: '333:di-token')->sendMessage([
            'text' => 'DI dynamic send',
        ]);

        $firstBody = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $secondBody = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot222:facade-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('-1001234567890', $firstBody['chat_id']);
        $this->assertSame('Facade dynamic send', $firstBody['text']);
        $this->assertSame('/bot333:di-token/sendMessage', $history[1]['request']->getUri()->getPath());
        $this->assertSame('-1009876543210', $secondBody['chat_id']);
        $this->assertSame('DI dynamic send', $secondBody['text']);
    }

    public function test_laravel_resolved_client_logs_api_failures_when_logging_is_enabled(): void
    {
        $history = [];
        $logger = new TelegramBotServiceProviderTestLogger();

        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');
        config()->set('telegram-bot.logging.enabled', true);

        $this->app->bind(ClientInterface::class, function () use (&$history): ClientInterface {
            return $this->fakeHttpClient([
                new Response(400, [], json_encode([
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: chat not found',
                ], JSON_THROW_ON_ERROR)),
            ], $history);
        });
        $this->app->instance(LoggerInterface::class, $logger);

        try {
            app(TelegramBotClient::class)->getMe();
            $this->fail('Expected Telegram Bot API exception was not thrown.');
        } catch (TelegramBotApiException) {
            $this->assertSame('warning', $logger->records[0]['level']);
            $this->assertSame('Telegram Bot API request failed.', $logger->records[0]['message']);
            $this->assertSame('getMe', $logger->records[0]['context']['method']);
        }
    }

    public function test_laravel_resolved_client_dispatches_observability_events_when_enabled(): void
    {
        Event::fake();

        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');
        config()->set('telegram-bot.observability.enabled', true);

        $this->app->bind(ClientInterface::class, function (): ClientInterface {
            return $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => ['id' => 1]], JSON_THROW_ON_ERROR)),
            ]);
        });

        app(TelegramBotClient::class)->getMe();

        Event::assertDispatched(
            TelegramBotApiRequestRecorded::class,
            static fn (TelegramBotApiRequestRecorded $event): bool => $event->telemetry->method === 'getMe'
                && $event->telemetry->ok === true
                && $event->telemetry->attempts === 1,
        );
    }

    public function test_laravel_resolved_client_uses_configured_rate_limiter(): void
    {
        $history = [];

        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');
        config()->set('telegram-bot.rate_limit.enabled', true);
        config()->set('telegram-bot.rate_limit.max_attempts', 1);
        config()->set('telegram-bot.rate_limit.decay_seconds', 60);
        config()->set('telegram-bot.rate_limit.key_prefix', 'telegram-bot:test-rate-limit');

        $this->app->bind(ClientInterface::class, function () use (&$history): ClientInterface {
            return $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ], $history);
        });

        app(TelegramBotClient::class)->getMe();

        $this->expectException(TelegramBotRateLimitException::class);

        app(TelegramBotClient::class)->getMe();
    }

    public function test_laravel_rate_limiter_scopes_attempts_by_bot_method_and_chat(): void
    {
        $history = [];

        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');
        config()->set('telegram-bot.rate_limit.enabled', true);
        config()->set('telegram-bot.rate_limit.max_attempts', 1);
        config()->set('telegram-bot.rate_limit.decay_seconds', 60);
        config()->set('telegram-bot.rate_limit.key_prefix', 'telegram-bot:test-context-rate-limit');

        $this->app->bind(ClientInterface::class, function () use (&$history): ClientInterface {
            return $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ], $history);
        });

        app(TelegramBotClient::class)->sendMessage(['chat_id' => '100', 'text' => 'First chat']);
        app(TelegramBotClient::class)->sendMessage(['chat_id' => '200', 'text' => 'Second chat']);

        $this->expectException(TelegramBotRateLimitException::class);

        app(TelegramBotClient::class)->sendMessage(['chat_id' => '100', 'text' => 'First chat again']);
    }

    public function test_laravel_config_accessor_exposes_typed_config_and_validation_issues(): void
    {
        config()->set('telegram-bot.default', 'support');
        config()->set('telegram-bot.bots.support', [
            'token' => '123456:test-token',
            'api_url' => 'https://api.telegram.test',
            'timeout' => 5,
        ]);
        config()->set('telegram-bot.channels.alerts', [
            'bot' => 'support',
            'chat_id' => '-1001234567890',
        ]);
        config()->set('telegram-bot.webhook.secret_token', 'invalid secret');
        config()->set('telegram-bot.webhook.require_secret', true);

        $config = TelegramBotLaravelConfig::fromArray(config('telegram-bot'));

        $this->assertSame('support', $config->defaultBot());
        $this->assertSame('123456:test-token', $config->bot()->token);
        $this->assertSame('-1001234567890', $config->channel('alerts')->chatId);
        $this->assertContains('Webhook secret contains characters Telegram will not accept.', $config->validationIssues());
    }

    /**
     * @param  list<Response>  $responses
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function fakeHttpClient(array $responses, array &$history = []): Client
    {
        $handler = HandlerStack::create(new MockHandler($responses));
        $handler->push(Middleware::history($history));

        return new Client([
            'handler' => $handler,
            'http_errors' => false,
        ]);
    }
}

final readonly class TelegramBotDiConsumer
{
    public function __construct(
        public TelegramBotService $telegram,
        public TelegramBotManager $manager,
        public TelegramBotManagerContract $managerContract,
        public TelegramBotClientContract $clientContract,
        public TelegramBotClient $client,
        public TelegramMiniAppInitDataValidator $miniAppValidator,
        public TelegramStartPayloadSigner $startPayloadSigner,
    ) {
        //
    }
}

final class TelegramBotServiceProviderTestLogger extends AbstractLogger
{
    /**
     * @var list<array{level: string, message: string, context: array<string, mixed>}>
     */
    public array $records = [];

    /**
     * @param  array<string, mixed>  $context
     */
    #[Override]
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->records[] = [
            'level' => (string) $level,
            'message' => (string) $message,
            'context' => $context,
        ];
    }
}
