<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotTransportException;
use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramBotClientTest extends TestCase
{
    public function test_sends_json_requests_and_returns_result(): void
    {
        $history = [];
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => ['id' => 1]], JSON_THROW_ON_ERROR)),
            ], $history),
        );

        $result = $client->call(TelegramBotApiMethod::getMe);

        $this->assertSame(['id' => 1], $result);
        $this->assertSame('/bot123456:test-token/getMe', $history[0]['request']->getUri()->getPath());
    }

    public function test_sends_multipart_requests_for_input_files(): void
    {
        $history = [];
        $path = tempnam(sys_get_temp_dir(), 'telegram-test-');
        file_put_contents($path, 'content');

        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ], $history),
        );

        $client->sendDocument(TelegramBotRequestData::fromArray([
            'chat_id' => '100',
            'document' => InputFile::fromPath($path, 'file.txt', 'text/plain'),
        ]));

        $this->assertStringStartsWith('multipart/form-data;', $history[0]['request']->getHeaderLine('Content-Type'));

        unlink($path);
    }

    public function test_sends_multipart_requests_for_nested_input_files(): void
    {
        $history = [];
        $path = tempnam(sys_get_temp_dir(), 'telegram-test-');
        file_put_contents($path, 'photo-content');

        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ], $history),
        );

        try {
            $client->sendMediaGroup([
                'chat_id' => '100',
                'media' => [
                    [
                        'type' => 'photo',
                        'media' => InputFile::fromPath($path, 'photo.jpg', 'image/jpeg'),
                    ],
                ],
            ]);

            $body = (string) $history[0]['request']->getBody();

            $this->assertStringStartsWith('multipart/form-data;', $history[0]['request']->getHeaderLine('Content-Type'));
            $this->assertStringContainsString('name="media"', $body);
            $this->assertStringContainsString('"media":"attach:\/\/file_0"', $body);
            $this->assertStringContainsString('name="file_0"', $body);
            $this->assertStringContainsString('filename="photo.jpg"', $body);
            $this->assertStringContainsString('Content-Type: image/jpeg', $body);
        } finally {
            unlink($path);
        }
    }

    public function test_throws_api_exception_for_failed_responses(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode([
                    'ok' => false,
                    'error_code' => 429,
                    'description' => 'Too Many Requests',
                    'parameters' => ['retry_after' => 30],
                ], JSON_THROW_ON_ERROR)),
            ]),
        );

        $this->expectException(TelegramBotApiException::class);

        $client->getMe();
    }

    public function test_throws_api_exception_for_failed_responses_when_http_client_enables_http_errors(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(429, [], json_encode([
                    'ok' => false,
                    'error_code' => 429,
                    'description' => 'Too Many Requests',
                    'parameters' => ['retry_after' => 30],
                ], JSON_THROW_ON_ERROR)),
            ], httpErrors: true),
        );

        try {
            $client->getMe();
            $this->fail('Expected Telegram Bot API exception was not thrown.');
        } catch (TelegramBotApiException $exception) {
            $this->assertSame(429, $exception->telegramErrorCode());
            $this->assertSame(['retry_after' => 30], $exception->parameters());
        }
    }

    public function test_throws_transport_exception_for_failed_response_without_description(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(400, [], json_encode(['ok' => false, 'error_code' => 400], JSON_THROW_ON_ERROR)),
            ]),
        );

        $this->expectException(TelegramBotTransportException::class);
        $this->expectExceptionMessage('Telegram Bot API failed response did not contain a string description field.');

        $client->getMe();
    }

    public function test_throws_transport_exception_for_success_response_without_result(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true], JSON_THROW_ON_ERROR)),
            ]),
        );

        $this->expectException(TelegramBotTransportException::class);
        $this->expectExceptionMessage('Telegram Bot API successful response did not contain a result field.');

        $client->getMe();
    }

    /**
     * @param  list<Response>  $responses
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function fakeHttpClient(array $responses, array &$history = [], bool $httpErrors = false): Client
    {
        $handler = HandlerStack::create(new MockHandler($responses));
        $handler->push(Middleware::history($history));

        return new Client([
            'handler' => $handler,
            'http_errors' => $httpErrors,
        ]);
    }
}
