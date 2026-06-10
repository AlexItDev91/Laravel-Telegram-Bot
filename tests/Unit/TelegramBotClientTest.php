<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotObserver;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotRateLimiter;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestTelemetryData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotRateLimitException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotTransportException;
use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotRetryPolicy;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Log\AbstractLogger;
use Stringable;

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

    public function test_sends_fluent_messages_directly_when_chat_id_is_present(): void
    {
        $history = [];
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => ['message_id' => 1]], JSON_THROW_ON_ERROR)),
            ], $history),
        );

        $result = $client->send(
            TelegramMessage::text('Direct hello')
                ->to('123456789')
                ->silent(),
        );

        $body = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame(['message_id' => 1], $result);
        $this->assertSame('/bot123456:test-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('123456789', $body['chat_id']);
        $this->assertSame('Direct hello', $body['text']);
        $this->assertTrue($body['disable_notification']);
    }

    public function test_sends_common_shortcut_messages_directly_when_chat_id_is_present(): void
    {
        $history = [];
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => ['message_id' => 1]], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => ['message_id' => 2]], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => ['message_id' => 3]], JSON_THROW_ON_ERROR)),
            ], $history),
        );

        $client->text('Direct hello', to: '123456789', messageThreadId: '42');
        $client->photo('photo-file-id', 'Daily report', to: '123456789');
        $client->document('document-file-id', 'Invoice', to: '123456789');

        $firstBody = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $secondBody = json_decode((string) $history[1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $thirdBody = json_decode((string) $history[2]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot123456:test-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame('123456789', $firstBody['chat_id']);
        $this->assertSame('42', $firstBody['message_thread_id']);
        $this->assertSame('Direct hello', $firstBody['text']);

        $this->assertSame('/bot123456:test-token/sendPhoto', $history[1]['request']->getUri()->getPath());
        $this->assertSame('123456789', $secondBody['chat_id']);
        $this->assertSame('photo-file-id', $secondBody['photo']);
        $this->assertSame('Daily report', $secondBody['caption']);

        $this->assertSame('/bot123456:test-token/sendDocument', $history[2]['request']->getUri()->getPath());
        $this->assertSame('123456789', $thirdBody['chat_id']);
        $this->assertSame('document-file-id', $thirdBody['document']);
        $this->assertSame('Invoice', $thirdBody['caption']);
    }

    public function test_direct_fluent_messages_require_a_chat_id(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must define a chat_id with to()');

        $client->send(TelegramMessage::text('Missing destination'));
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

    public function test_uses_lazy_streams_for_input_file_multipart_parts(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'telegram-test-');
        file_put_contents($path, 'content');

        try {
            $multipart = TelegramBotRequestData::fromArray([
                'document' => InputFile::fromPath($path, 'file.txt', 'text/plain'),
            ])->multipart();

            $this->assertInstanceOf(\GuzzleHttp\Psr7\LazyOpenStream::class, $multipart[0]['contents']);
            $this->assertSame(0, $this->openStreamsForPath($path));
        } finally {
            unlink($path);
        }
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

    public function test_logs_failed_api_responses_without_token_or_payload_values(): void
    {
        $logger = new TelegramBotTestLogger();
        $client = TelegramBotClient::make(
            token: '123456:secret-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode([
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: chat not found',
                ], JSON_THROW_ON_ERROR)),
            ]),
            logger: $logger,
        );

        try {
            $client->sendMessage([
                'chat_id' => '-1001234567890',
                'text' => 'Private alert body',
            ]);
            $this->fail('Expected Telegram Bot API exception was not thrown.');
        } catch (TelegramBotApiException) {
            $record = $logger->records[0] ?? null;

            $this->assertNotNull($record);
            $this->assertSame('warning', $record['level']);
            $this->assertSame('Telegram Bot API request failed.', $record['message']);
            $this->assertSame('sendMessage', $record['context']['method']);
            $this->assertSame(400, $record['context']['telegram_error_code']);
            $this->assertStringNotContainsString('123456:secret-token', json_encode($record, JSON_THROW_ON_ERROR));
            $this->assertStringNotContainsString('-1001234567890', json_encode($record, JSON_THROW_ON_ERROR));
            $this->assertStringNotContainsString('Private alert body', json_encode($record, JSON_THROW_ON_ERROR));
        }
    }

    public function test_logs_transport_response_errors_without_body_or_token(): void
    {
        $logger = new TelegramBotTestLogger();
        $client = TelegramBotClient::make(
            token: '123456:secret-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(502, [], 'proxy leaked token 123456:secret-token'),
            ]),
            logger: $logger,
        );

        try {
            $client->getMe();
            $this->fail('Expected Telegram Bot transport exception was not thrown.');
        } catch (TelegramBotTransportException) {
            $record = $logger->records[0] ?? null;

            $this->assertNotNull($record);
            $this->assertSame('error', $record['level']);
            $this->assertSame('Telegram Bot API returned a non-JSON response.', $record['message']);
            $this->assertSame('getMe', $record['context']['method']);
            $this->assertSame(502, $record['context']['status_code']);
            $this->assertStringNotContainsString('123456:secret-token', json_encode($record, JSON_THROW_ON_ERROR));
            $this->assertStringNotContainsString('proxy leaked token', json_encode($record, JSON_THROW_ON_ERROR));
        }
    }

    public function test_transport_exception_message_does_not_expose_bot_token(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:secret-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new RequestException(
                    'Network failure while requesting https://api.telegram.test/bot123456:secret-token/getMe or https://api.telegram.test/bot123456%3Asecret-token/getMe',
                    new Request('POST', 'https://api.telegram.test/bot123456:secret-token/getMe'),
                ),
            ]),
        );

        try {
            $client->getMe();
            $this->fail('Expected Telegram Bot transport exception was not thrown.');
        } catch (TelegramBotTransportException $exception) {
            $this->assertStringNotContainsString('123456:secret-token', $exception->getMessage());
            $this->assertStringNotContainsString('123456%3Asecret-token', $exception->getMessage());
            $this->assertStringContainsString('<redacted-bot-token>', $exception->getMessage());
            $this->assertInstanceOf(RequestException::class, $exception->getPrevious());
        }
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
            $this->assertSame(30, $exception->retryAfter());
            $this->assertNull($exception->migrateToChatId());
        }
    }

    public function test_api_exception_exposes_response_parameter_helpers(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(400, [], json_encode([
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: group chat was upgraded to a supergroup chat',
                    'parameters' => [
                        'migrate_to_chat_id' => '-1001234567890',
                    ],
                ], JSON_THROW_ON_ERROR)),
            ]),
        );

        try {
            $client->sendMessage([
                'chat_id' => '-1234567890',
                'text' => 'Hello',
            ]);
            $this->fail('Expected Telegram Bot API exception was not thrown.');
        } catch (TelegramBotApiException $exception) {
            $this->assertSame('-1001234567890', $exception->migrateToChatId());
            $this->assertNull($exception->retryAfter());
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

    public function test_retries_retryable_telegram_api_responses_without_sleep_when_configured(): void
    {
        $history = [];
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(429, [], json_encode([
                    'ok' => false,
                    'error_code' => 429,
                    'description' => 'Too Many Requests',
                    'parameters' => ['retry_after' => 10],
                ], JSON_THROW_ON_ERROR)),
                new Response(200, [], json_encode(['ok' => true, 'result' => ['id' => 1]], JSON_THROW_ON_ERROR)),
            ], $history),
            retryPolicy: new TelegramBotRetryPolicy(enabled: true, maxAttempts: 2, sleep: false),
        );

        $this->assertSame(['id' => 1], $client->getMe());
        $this->assertCount(2, $history);
    }

    public function test_observer_records_sanitized_request_telemetry(): void
    {
        $observer = new TelegramBotTestObserver();
        $client = TelegramBotClient::make(
            token: '123456:secret-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ]),
            observer: $observer,
        );

        $client->sendMessage([
            'chat_id' => '-1001234567890',
            'text' => 'Private alert body',
        ]);

        $this->assertCount(1, $observer->records);
        $this->assertSame('sendMessage', $observer->records[0]->method);
        $this->assertTrue($observer->records[0]->ok);
        $this->assertSame(1, $observer->records[0]->attempts);
        $this->assertStringNotContainsString('123456:secret-token', json_encode($observer->records[0]->toArray(), JSON_THROW_ON_ERROR));
        $this->assertStringNotContainsString('-1001234567890', json_encode($observer->records[0]->toArray(), JSON_THROW_ON_ERROR));
        $this->assertStringNotContainsString('Private alert body', json_encode($observer->records[0]->toArray(), JSON_THROW_ON_ERROR));
    }

    public function test_client_uses_injected_rate_limiter(): void
    {
        $limiter = new TelegramBotBlockingRateLimiter();
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
            ]),
            rateLimiter: $limiter,
        );

        $this->expectException(TelegramBotRateLimitException::class);

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

    private function openStreamsForPath(string $path): int
    {
        $streams = 0;

        foreach (get_resources('stream') as $resource) {
            $metadata = @stream_get_meta_data($resource);

            if (($metadata['uri'] ?? null) === $path) {
                $streams++;
            }
        }

        return $streams;
    }
}

final class TelegramBotTestLogger extends AbstractLogger
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

final class TelegramBotTestObserver implements TelegramBotObserver
{
    /**
     * @var list<TelegramBotRequestTelemetryData>
     */
    public array $records = [];

    #[Override]
    public function record(TelegramBotRequestTelemetryData $telemetry): void
    {
        $this->records[] = $telemetry;
    }
}

final class TelegramBotBlockingRateLimiter implements TelegramBotRateLimiter
{
    #[Override]
    public function throttle(string $method, Closure $_next): mixed
    {
        throw new TelegramBotRateLimitException("Blocked $method.", 1);
    }
}
