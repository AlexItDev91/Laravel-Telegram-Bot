<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodRegistry;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramBotApiSurfaceTest extends TestCase
{
    public function test_exposes_every_registered_bot_api_method_as_native_method(): void
    {
        $this->assertSame('10.0', TelegramBotApiMethodRegistry::BOT_API_VERSION);
        $this->assertSame('2026-05-08', TelegramBotApiMethodRegistry::BOT_API_RELEASE_DATE);
        $this->assertCount(176, TelegramBotApiMethod::cases());

        foreach (TelegramBotApiMethod::cases() as $method) {
            $this->assertTrue(method_exists(TelegramBotClient::class, $method->value), "Missing method [{$method->value}].");
            $this->assertTrue(TelegramBotApiMethodRegistry::supports($method->value));
        }
    }

    #[DataProvider('botApiMethodProvider')]
    public function test_every_native_method_calls_its_matching_telegram_endpoint(TelegramBotApiMethod $method): void
    {
        $history = [];
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient([
                new Response(200, [], json_encode(['ok' => true, 'result' => ['method' => $method->value]], JSON_THROW_ON_ERROR)),
            ], $history),
        );

        $result = $client->{$method->value}(['probe' => $method->value]);

        $this->assertSame(['method' => $method->value], $result);
        $this->assertSame("/bot123456:test-token/{$method->value}", $history[0]['request']->getUri()->getPath());
        $this->assertSame(['probe' => $method->value], json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR));
    }

    public function test_api_documentation_links_every_method_to_the_official_telegram_source(): void
    {
        $documentation = file_get_contents(__DIR__.'/../../docs/API.md');

        $this->assertIsString($documentation);
        $this->assertStringContainsString('https://core.telegram.org/bots/api', $documentation);
        $this->assertStringContainsString('https://core.telegram.org/bots/api-changelog', $documentation);

        foreach (TelegramBotApiMethod::cases() as $method) {
            $this->assertStringContainsString(
                sprintf('| `%s` | `%s(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#%s) |', $method->value, $method->value, strtolower($method->value)),
                $documentation,
            );
        }
    }

    /**
     * @return array<string, array{method: TelegramBotApiMethod}>
     */
    public static function botApiMethodProvider(): array
    {
        $methods = [];

        foreach (TelegramBotApiMethod::cases() as $method) {
            $methods[$method->value] = ['method' => $method];
        }

        return $methods;
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
