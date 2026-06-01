<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
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
use Psr\Http\Message\RequestInterface;

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

    /**
     * @param  list<Response>  $responses
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function fakeHttpClient(array $responses, array &$history): Client
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
    ) {
        //
    }
}
