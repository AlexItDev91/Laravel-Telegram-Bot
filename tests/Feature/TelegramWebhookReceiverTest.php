<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

class TelegramWebhookReceiverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        TelegramWebhookTestHandler::reset();
    }

    public function test_default_webhook_route_accepts_updates_and_dispatches_event(): void
    {
        Event::fake();

        $response = $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1001,
            'message' => [
                'message_id' => 10,
                'text' => 'Hello',
            ],
        ]);

        $response->assertOk()->assertExactJson(['ok' => true]);

        Event::assertDispatched(TelegramWebhookReceived::class, function (TelegramWebhookReceived $event): bool {
            return $event->update->updateId() === 1001
                && $event->update->type() === 'message'
                && $event->botName === 'default';
        });
    }

    public function test_webhook_secret_token_is_required_when_configured(): void
    {
        $logger = new TelegramWebhookTestLogger();
        $this->app->instance(LoggerInterface::class, $logger);

        config()->set('telegram-bot.webhook.secret_token', 'secret-token');

        $this->postJson('/telegram-bot/webhook', ['update_id' => 1001])
            ->assertForbidden();

        $this->assertSame('warning', $logger->records[0]['level']);
        $this->assertSame('Telegram webhook rejected because the secret token is invalid.', $logger->records[0]['message']);
        $this->assertStringNotContainsString('secret-token', json_encode($logger->records[0], JSON_THROW_ON_ERROR));

        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'secret-token')
            ->postJson('/telegram-bot/webhook', ['update_id' => 1001])
            ->assertOk()
            ->assertExactJson(['ok' => true]);
    }

    public function test_webhook_secret_token_is_required_by_default_in_production(): void
    {
        config()->set('app.env', 'production');
        config()->set('telegram-bot.webhook.secret_token', null);
        config()->set('telegram-bot.webhook.require_secret', null);

        $this->postJson('/telegram-bot/webhook', ['update_id' => 1001])
            ->assertForbidden();
    }

    public function test_webhook_handler_receives_update_and_may_return_response_payload(): void
    {
        config()->set('telegram-bot.webhook.handler', TelegramWebhookTestHandler::class);
        config()->set('telegram-bot.webhook.bot', 'support');

        $response = $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1002,
            'callback_query' => [
                'id' => 'callback-id',
            ],
        ]);

        $response->assertOk()->assertExactJson([
            'handled' => true,
            'type' => 'callback_query',
        ]);

        $this->assertSame(1002, TelegramWebhookTestHandler::$update?->updateId());
        $this->assertSame('callback_query', TelegramWebhookTestHandler::$update?->type());
        $this->assertSame('support', TelegramWebhookTestHandler::$botName);
    }

    public function test_webhook_route_rejects_non_json_payloads(): void
    {
        $logger = new TelegramWebhookTestLogger();
        $this->app->instance(LoggerInterface::class, $logger);

        $this->call('POST', '/telegram-bot/webhook', [], [], [], [], 'not-json')
            ->assertUnprocessable();

        $this->assertSame('warning', $logger->records[0]['level']);
        $this->assertSame('Telegram webhook rejected because the update payload is invalid.', $logger->records[0]['message']);
        $this->assertStringNotContainsString('not-json', json_encode($logger->records[0], JSON_THROW_ON_ERROR));
    }

    public function test_webhook_logs_invalid_handler_configuration_without_failing_telegram_response(): void
    {
        $logger = new TelegramWebhookTestLogger();
        $this->app->instance(LoggerInterface::class, $logger);

        config()->set('telegram-bot.webhook.handler', ['not-callable']);

        $this->postJson('/telegram-bot/webhook', ['update_id' => 1003])
            ->assertOk()
            ->assertExactJson(['ok' => true]);

        $this->assertSame('warning', $logger->records[0]['level']);
        $this->assertSame('Telegram webhook handler is configured but is not resolvable or callable.', $logger->records[0]['message']);
    }

    public function test_webhook_logs_handler_failures_without_payload_values(): void
    {
        $logger = new TelegramWebhookTestLogger();
        $this->app->instance(LoggerInterface::class, $logger);

        config()->set('telegram-bot.webhook.handler', TelegramWebhookFailingHandler::class);

        $this->withoutExceptionHandling();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Handler failed.');

        try {
            $this->postJson('/telegram-bot/webhook', [
                'update_id' => 1004,
                'message' => [
                    'text' => 'Private inbound text',
                ],
            ]);
        } finally {
            $record = $logger->records[0] ?? null;

            $this->assertNotNull($record);
            $this->assertSame('error', $record['level']);
            $this->assertSame('Telegram webhook handler failed.', $record['message']);
            $this->assertSame(1004, $record['context']['update_id']);
            $this->assertSame('message', $record['context']['update_type']);
            $this->assertStringNotContainsString('Private inbound text', json_encode($record, JSON_THROW_ON_ERROR));
        }
    }
}

final class TelegramWebhookTestHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static ?string $botName = null;

    public static function reset(): void
    {
        self::$update = null;
        self::$botName = null;
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;
        self::$botName = $botName;

        return [
            'handled' => true,
            'type' => $update->type(),
        ];
    }
}

final class TelegramWebhookFailingHandler implements TelegramWebhookHandler
{
    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        throw new \RuntimeException('Handler failed.');
    }
}

final class TelegramWebhookTestLogger extends AbstractLogger
{
    /**
     * @var list<array{level: string, message: string, context: array<string, mixed>}>
     */
    public array $records = [];

    /**
     * @param  array<string, mixed>  $context
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->records[] = [
            'level' => (string) $level,
            'message' => (string) $message,
            'context' => $context,
        ];
    }
}
