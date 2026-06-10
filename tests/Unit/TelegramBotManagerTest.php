<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramBotManagerTest extends TestCase
{
    public function test_resolves_named_bots_and_channels(): void
    {
        $history = [];
        $http = $this->fakeHttpClient([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ], $history);

        $manager = new TelegramBotManager([
            'default' => 'support',
            'bots' => [
                'support' => ['token' => '111:support', 'api_url' => 'https://api.telegram.test'],
            ],
            'channels' => [
                'inbox' => [
                    'bot' => 'support',
                    'chat_id' => '-1009007199254740991',
                    'message_thread_id' => '42',
                ],
            ],
        ], static fn (TelegramBotConfigData $config): TelegramBotClient => new TelegramBotClient($config, $http));

        $manager->channel('inbox')->sendMessage(['text' => 'New inbound email']);

        $body = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot111:support/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('-1009007199254740991', $body['chat_id']);
        $this->assertSame('42', $body['message_thread_id']);
    }

    public function test_named_bot_config_falls_back_to_shared_values_when_values_are_empty(): void
    {
        $history = [];
        $http = $this->fakeHttpClient([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ], $history);

        $manager = new TelegramBotManager([
            'token' => '111:shared',
            'api_url' => 'https://api.telegram.test',
            'bots' => [
                'default' => ['token' => null, 'api_url' => '', 'timeout' => null],
            ],
        ], static fn (TelegramBotConfigData $config): TelegramBotClient => new TelegramBotClient($config, $http));

        $manager->bot()->getMe();

        $this->assertSame('/bot111:shared/getMe', $history[0]['request']->getUri()->getPath());
    }

    public function test_sends_to_dynamic_chat_with_dynamic_bot_token_without_configured_bot(): void
    {
        $history = [];
        $http = $this->fakeHttpClient([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ], $history);

        $manager = new TelegramBotManager([
            'api_url' => 'https://api.telegram.test',
        ], static fn (TelegramBotConfigData $config): TelegramBotClient => new TelegramBotClient($config, $http));

        $manager->to('-1001234567890', token: '222:dynamic-token')->sendMessage([
            'text' => 'Dynamic message',
        ]);

        $body = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot222:dynamic-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('-1001234567890', $body['chat_id']);
        $this->assertSame('Dynamic message', $body['text']);
    }

    public function test_dynamic_bot_token_can_override_configured_channel_bot(): void
    {
        $history = [];
        $http = $this->fakeHttpClient([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ], $history);

        $manager = new TelegramBotManager([
            'api_url' => 'https://api.telegram.test',
            'bots' => [
                'support' => ['token' => '111:support'],
            ],
            'channels' => [
                'alerts' => [
                    'bot' => 'support',
                    'chat_id' => '-1001234567890',
                ],
            ],
        ], static fn (TelegramBotConfigData $config): TelegramBotClient => new TelegramBotClient($config, $http));

        $manager->channel('alerts', token: '222:dynamic-token')->sendMessage([
            'text' => 'Channel override',
        ]);

        $body = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot222:dynamic-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('-1001234567890', $body['chat_id']);
        $this->assertSame('Channel override', $body['text']);
    }

    public function test_dynamic_destination_rejects_mixed_named_bot_and_token(): void
    {
        $manager = new TelegramBotManager([
            'api_url' => 'https://api.telegram.test',
        ], static fn (TelegramBotConfigData $config): TelegramBotClient => new TelegramBotClient($config));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Use either a configured Telegram bot name or a dynamic bot token, not both.');

        $manager->to('-1001234567890', bot: 'support', token: '222:dynamic-token');
    }

    public function test_sends_fluent_messages_to_configured_and_dynamic_destinations(): void
    {
        $history = [];
        $http = $this->fakeHttpClient([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ], $history);

        $manager = new TelegramBotManager([
            'api_url' => 'https://api.telegram.test',
            'bots' => [
                'support' => ['token' => '111:support'],
            ],
            'channels' => [
                'alerts' => [
                    'bot' => 'support',
                    'chat_id' => '-1001234567890',
                ],
            ],
        ], static fn (TelegramBotConfigData $config): TelegramBotClient => new TelegramBotClient($config, $http));

        $manager->channel('alerts')->send(TelegramMessage::text('Deploy finished'));
        $manager->to('-1009876543210', token: '222:dynamic-token')->send(
            TelegramMessage::photo('photo-file-id')->caption('Daily report'),
        );
        $manager->botToken('333:direct-token')->send(
            TelegramMessage::document('document-file-id')->to('-1005555555555')->caption('Invoice'),
        );

        $firstBody = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $secondBody = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $thirdBody = json_decode((string) $history[2]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot111:support/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('-1001234567890', $firstBody['chat_id']);
        $this->assertSame('Deploy finished', $firstBody['text']);

        $this->assertSame('/bot222:dynamic-token/sendPhoto', $history[1]['request']->getUri()->getPath());
        $this->assertSame('-1009876543210', $secondBody['chat_id']);
        $this->assertSame('photo-file-id', $secondBody['photo']);
        $this->assertSame('Daily report', $secondBody['caption']);

        $this->assertSame('/bot333:direct-token/sendDocument', $history[2]['request']->getUri()->getPath());
        $this->assertSame('-1005555555555', $thirdBody['chat_id']);
        $this->assertSame('document-file-id', $thirdBody['document']);
        $this->assertSame('Invoice', $thirdBody['caption']);
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
