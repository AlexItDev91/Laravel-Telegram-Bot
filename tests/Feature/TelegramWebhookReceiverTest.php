<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Event;

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
        config()->set('telegram-bot.webhook.secret_token', 'secret-token');

        $this->postJson('/telegram-bot/webhook', ['update_id' => 1001])
            ->assertForbidden();

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
        $this->call('POST', '/telegram-bot/webhook', [], [], [], [], 'not-json')
            ->assertUnprocessable();
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
