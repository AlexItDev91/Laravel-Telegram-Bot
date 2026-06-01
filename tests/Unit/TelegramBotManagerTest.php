<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
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
