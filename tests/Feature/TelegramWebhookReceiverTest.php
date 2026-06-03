<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookDuplicateSkipped;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookFailed;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookHandled;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookQueued;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use AlexItDev91\LaravelTelegramBot\Laravel\Jobs\TelegramWebhookJob;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookProcessor;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Bus;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

class TelegramWebhookReceiverTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        TelegramWebhookTestHandler::reset();
        TelegramWebhookTypedAccessorHandler::reset();
        TelegramWebhookCallbackQueryHandler::reset();
        TelegramWebhookPaymentQueryHandler::reset();
        TelegramWebhookMembershipHandler::reset();
        TelegramWebhookCountingHandler::reset();
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
        Event::assertDispatched(TelegramWebhookHandled::class, function (TelegramWebhookHandled $event): bool {
            return $event->update->updateId() === 1001
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

    public function test_webhook_handler_can_use_typed_effective_update_accessors(): void
    {
        config()->set('telegram-bot.webhook.handler', TelegramWebhookTypedAccessorHandler::class);
        config()->set('telegram-bot.webhook.bot', 'support');

        $response = $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1005,
            'message' => [
                'message_id' => 55,
                'message_thread_id' => 9,
                'text' => '/start',
                'from' => [
                    'id' => 987654321,
                    'is_bot' => false,
                    'first_name' => 'Alex',
                    'username' => 'alex',
                ],
                'chat' => [
                    'id' => -1001234567890,
                    'type' => 'supergroup',
                    'title' => 'Support',
                ],
            ],
        ]);

        $response->assertOk()->assertExactJson([
            'bot' => 'support',
            'chat_id' => '-1001234567890',
            'chat_type' => 'supergroup',
            'message_id' => 55,
            'message_thread_id' => 9,
            'text' => '/start',
            'user_id' => '987654321',
            'username' => 'alex',
        ]);

        $this->assertSame(1005, TelegramWebhookTypedAccessorHandler::$update?->updateId());
    }

    public function test_webhook_handler_can_use_typed_callback_query_accessor(): void
    {
        config()->set('telegram-bot.webhook.handler', TelegramWebhookCallbackQueryHandler::class);
        config()->set('telegram-bot.webhook.bot', 'support');

        $response = $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1006,
            'callback_query' => [
                'id' => 'callback-id',
                'from' => [
                    'id' => 987654321,
                    'is_bot' => false,
                    'first_name' => 'Alex',
                    'username' => 'alex',
                ],
                'message' => [
                    'message_id' => 56,
                    'text' => 'Choose',
                    'chat' => [
                        'id' => -1001234567890,
                        'type' => 'supergroup',
                    ],
                ],
                'chat_instance' => 'chat-instance',
                'data' => 'menu:settings',
            ],
        ]);

        $response->assertOk()->assertExactJson([
            'bot' => 'support',
            'callback_id' => 'callback-id',
            'chat_id' => '-1001234567890',
            'chat_instance' => 'chat-instance',
            'data' => 'menu:settings',
            'message_id' => 56,
            'user_id' => '987654321',
        ]);

        $this->assertSame(1006, TelegramWebhookCallbackQueryHandler::$update?->updateId());
    }

    public function test_webhook_handler_can_use_typed_payment_query_accessors(): void
    {
        config()->set('telegram-bot.webhook.handler', TelegramWebhookPaymentQueryHandler::class);
        config()->set('telegram-bot.webhook.bot', 'shop');

        $response = $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1007,
            'pre_checkout_query' => [
                'id' => 'pre-checkout-id',
                'from' => [
                    'id' => 987654321,
                    'is_bot' => false,
                    'first_name' => 'Alex',
                ],
                'currency' => 'XTR',
                'total_amount' => 150,
                'invoice_payload' => 'order-100',
                'order_info' => [
                    'name' => 'Alex',
                    'email' => 'alex@example.com',
                ],
            ],
        ]);

        $response->assertOk()->assertExactJson([
            'bot' => 'shop',
            'currency' => 'XTR',
            'invoice_payload' => 'order-100',
            'order_email' => 'alex@example.com',
            'order_name' => 'Alex',
            'pre_checkout_id' => 'pre-checkout-id',
            'total_amount' => 150,
            'user_id' => '987654321',
        ]);

        $this->assertSame(1007, TelegramWebhookPaymentQueryHandler::$update?->updateId());
    }

    public function test_webhook_handler_can_use_typed_membership_accessors(): void
    {
        config()->set('telegram-bot.webhook.handler', TelegramWebhookMembershipHandler::class);
        config()->set('telegram-bot.webhook.bot', 'moderation');

        $response = $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1008,
            'chat_member' => [
                'chat' => [
                    'id' => -1001234567890,
                    'type' => 'supergroup',
                ],
                'from' => [
                    'id' => 987654321,
                    'is_bot' => false,
                    'first_name' => 'Alex',
                ],
                'date' => 1_780_000_000,
                'old_chat_member' => [
                    'status' => 'member',
                    'user' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Member'],
                ],
                'new_chat_member' => [
                    'status' => 'administrator',
                    'user' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Member'],
                    'can_delete_messages' => true,
                ],
            ],
        ]);

        $response->assertOk()->assertExactJson([
            'bot' => 'moderation',
            'chat_id' => '-1001234567890',
            'date' => 1_780_000_000,
            'member_user_id' => '123',
            'moderates_messages' => true,
            'from_user_id' => '987654321',
            'new_status' => 'administrator',
            'old_status' => 'member',
        ]);

        $this->assertSame(1008, TelegramWebhookMembershipHandler::$update?->updateId());
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

    public function test_webhook_route_rejects_payloads_without_integer_update_id(): void
    {
        $this->postJson('/telegram-bot/webhook', ['update_id' => '1001'])
            ->assertUnprocessable()
            ->assertExactJson(['ok' => false, 'description' => 'Invalid Telegram webhook update payload.']);
    }

    public function test_webhook_secret_token_configuration_must_match_telegram_contract(): void
    {
        $logger = new TelegramWebhookTestLogger();
        $this->app->instance(LoggerInterface::class, $logger);

        config()->set('telegram-bot.webhook.secret_token', 'invalid secret');

        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'invalid secret')
            ->postJson('/telegram-bot/webhook', ['update_id' => 1001])
            ->assertForbidden();

        $this->assertSame('warning', $logger->records[0]['level']);
        $this->assertSame('Telegram webhook rejected because the configured secret token is invalid.', $logger->records[0]['message']);
        $this->assertStringNotContainsString('invalid secret', json_encode($logger->records[0], JSON_THROW_ON_ERROR));
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

    public function test_webhook_can_queue_processing_without_calling_handler_inline(): void
    {
        Event::fake();
        Bus::fake();

        config()->set('telegram-bot.webhook.handler', TelegramWebhookTestHandler::class);
        config()->set('telegram-bot.webhook.bot', 'support');
        config()->set('telegram-bot.webhook.queue.enabled', true);
        config()->set('telegram-bot.webhook.queue.connection', 'redis');
        config()->set('telegram-bot.webhook.queue.queue', 'telegram-webhooks');
        config()->set('telegram-bot.webhook.queue.after_commit', true);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 1010,
            'message' => [
                'message_id' => 10,
                'text' => 'Queued update',
            ],
        ])->assertOk()->assertExactJson([
            'ok' => true,
            'queued' => true,
        ]);

        $this->assertNull(TelegramWebhookTestHandler::$update);

        Bus::assertDispatched(TelegramWebhookJob::class, function (TelegramWebhookJob $job): bool {
            return $job->botName === 'support'
                && $job->payload['update_id'] === 1010
                && $job->connection === 'redis'
                && $job->queue === 'telegram-webhooks'
                && $job->afterCommit === true;
        });
        Event::assertDispatched(TelegramWebhookQueued::class, function (TelegramWebhookQueued $event): bool {
            return $event->update->updateId() === 1010
                && $event->botName === 'support'
                && $event->connection === 'redis'
                && $event->queue === 'telegram-webhooks';
        });
    }

    public function test_queued_webhook_job_processes_configured_handler(): void
    {
        config()->set('telegram-bot.webhook.handler', TelegramWebhookTestHandler::class);

        $job = new TelegramWebhookJob([
            'update_id' => 1011,
            'callback_query' => [
                'id' => 'queued-callback',
            ],
        ], 'support');

        $job->handle($this->app->make(TelegramWebhookProcessor::class));

        $this->assertSame(1011, TelegramWebhookTestHandler::$update?->updateId());
        $this->assertSame('callback_query', TelegramWebhookTestHandler::$update?->type());
        $this->assertSame('support', TelegramWebhookTestHandler::$botName);
    }

    public function test_webhook_idempotency_skips_duplicate_updates(): void
    {
        Event::fake();

        config()->set('telegram-bot.webhook.handler', TelegramWebhookCountingHandler::class);
        config()->set('telegram-bot.webhook.idempotency.enabled', true);
        config()->set('telegram-bot.webhook.idempotency.ttl', 60);

        $payload = [
            'update_id' => 1012,
            'message' => [
                'message_id' => 12,
                'text' => 'Duplicate update',
            ],
        ];

        $this->postJson('/telegram-bot/webhook', $payload)
            ->assertOk()
            ->assertExactJson([
                'count' => 1,
                'update_id' => 1012,
            ]);

        $this->postJson('/telegram-bot/webhook', $payload)
            ->assertOk()
            ->assertExactJson([
                'duplicate' => true,
                'ok' => true,
            ]);

        $this->assertSame(1, TelegramWebhookCountingHandler::$count);
        Event::assertDispatched(TelegramWebhookDuplicateSkipped::class, function (TelegramWebhookDuplicateSkipped $event): bool {
            return $event->update->updateId() === 1012
                && $event->botName === 'default';
        });
    }

    public function test_webhook_logs_handler_failures_without_payload_values(): void
    {
        Event::fake();

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
            Event::assertDispatched(TelegramWebhookFailed::class, function (TelegramWebhookFailed $event): bool {
                return $event->update->updateId() === 1004
                    && $event->botName === 'default'
                    && $event->exception instanceof \RuntimeException;
            });
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

    #[\Override]
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
    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        throw new \RuntimeException('Handler failed.');
    }
}

final class TelegramWebhookCountingHandler implements TelegramWebhookHandler
{
    public static int $count = 0;

    public static function reset(): void
    {
        self::$count = 0;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$count++;

        return [
            'count' => self::$count,
            'update_id' => $update->updateId(),
        ];
    }
}

final class TelegramWebhookTypedAccessorHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static function reset(): void
    {
        self::$update = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;

        $message = $update->effectiveMessage();
        $chat = $update->effectiveChat();
        $user = $update->effectiveUser();

        return [
            'bot' => $botName,
            'message_id' => $message?->messageId(),
            'message_thread_id' => $message?->messageThreadId(),
            'text' => $message?->text(),
            'chat_id' => (string) $chat?->id(),
            'chat_type' => $chat?->type(),
            'user_id' => (string) $user?->id(),
            'username' => $user?->username(),
        ];
    }
}

final class TelegramWebhookCallbackQueryHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static function reset(): void
    {
        self::$update = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;

        $callbackQuery = $update->callbackQuery();

        return [
            'bot' => $botName,
            'callback_id' => $callbackQuery?->id(),
            'data' => $callbackQuery?->data(),
            'chat_instance' => $callbackQuery?->chatInstance(),
            'message_id' => $callbackQuery?->message()?->messageId(),
            'chat_id' => (string) $callbackQuery?->message()?->chat()?->id(),
            'user_id' => (string) $callbackQuery?->from()?->id(),
        ];
    }
}

final class TelegramWebhookPaymentQueryHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static function reset(): void
    {
        self::$update = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;

        $preCheckoutQuery = $update->preCheckoutQueryData();

        return [
            'bot' => $botName,
            'pre_checkout_id' => $preCheckoutQuery?->id(),
            'user_id' => (string) $preCheckoutQuery?->from()?->id(),
            'currency' => $preCheckoutQuery?->currency(),
            'total_amount' => $preCheckoutQuery?->totalAmount(),
            'invoice_payload' => $preCheckoutQuery?->invoicePayload(),
            'order_name' => $preCheckoutQuery?->orderInfoData()?->name(),
            'order_email' => $preCheckoutQuery?->orderInfoData()?->email(),
        ];
    }
}

final class TelegramWebhookMembershipHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static function reset(): void
    {
        self::$update = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;

        $chatMember = $update->chatMember();

        return [
            'bot' => $botName,
            'chat_id' => (string) $chatMember?->chat()?->id(),
            'from_user_id' => (string) $chatMember?->from()?->id(),
            'date' => $chatMember?->date(),
            'old_status' => $chatMember?->oldChatMemberData()?->status(),
            'new_status' => $chatMember?->newChatMemberData()?->status(),
            'member_user_id' => (string) $chatMember?->newChatMemberData()?->user()?->id(),
            'moderates_messages' => $chatMember?->newChatMemberData()?->canDeleteMessages(),
        ];
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
    #[\Override]
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->records[] = [
            'level' => (string) $level,
            'message' => (string) $message,
            'context' => $context,
        ];
    }
}
