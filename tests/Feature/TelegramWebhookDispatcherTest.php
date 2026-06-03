<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Attributes\TelegramUpdateHandler;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookCommand;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use Closure;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

class TelegramWebhookDispatcherTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        TelegramWebhookStartCommandHandler::reset();
        TelegramWebhookMessageUpdateHandler::reset();
        TelegramWebhookFallbackHandler::reset();
        TelegramWebhookRecordingMiddleware::reset();
        TelegramWebhookShortCircuitMiddleware::reset();
    }

    public function test_dispatches_configured_command_handlers_before_update_type_handlers(): void
    {
        config()->set('telegram-bot.webhook.bot', 'support');
        config()->set('telegram-bot.webhook.bot_username', 'support_bot');
        config()->set('telegram-bot.webhook.commands.start', TelegramWebhookStartCommandHandler::class);
        config()->set('telegram-bot.webhook.handlers.message', TelegramWebhookMessageUpdateHandler::class);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2001,
            'message' => [
                'message_id' => 10,
                'text' => '/start onboarding fast',
                'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Alex'],
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertExactJson([
            'arguments' => 'onboarding fast',
            'bot' => 'support',
            'command' => 'start',
            'message_id' => 10,
        ]);

        $this->assertSame(2001, TelegramWebhookStartCommandHandler::$update?->updateId());
        $this->assertSame('start', TelegramWebhookStartCommandHandler::$command?->name());
        $this->assertNull(TelegramWebhookMessageUpdateHandler::$update);
    }

    public function test_ignores_commands_addressed_to_another_bot_and_uses_update_handler(): void
    {
        config()->set('telegram-bot.webhook.bot_username', 'support_bot');
        config()->set('telegram-bot.webhook.commands.start', TelegramWebhookStartCommandHandler::class);
        config()->set('telegram-bot.webhook.handlers.message', TelegramWebhookMessageUpdateHandler::class);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2002,
            'message' => [
                'message_id' => 11,
                'text' => '/start@other_bot ignored',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertExactJson([
            'message_id' => 11,
            'text' => '/start@other_bot ignored',
            'type' => 'message',
        ]);

        $this->assertNull(TelegramWebhookStartCommandHandler::$update);
        $this->assertSame(2002, TelegramWebhookMessageUpdateHandler::$update?->updateId());
    }

    public function test_dispatches_fallback_handler_when_no_command_or_update_handler_matches(): void
    {
        config()->set('telegram-bot.webhook.fallback_handler', TelegramWebhookFallbackHandler::class);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2003,
            'poll' => [
                'id' => 'poll-id',
                'question' => 'Ship it?',
                'options' => [],
                'total_voter_count' => 0,
                'is_closed' => false,
                'is_anonymous' => true,
                'type' => 'regular',
                'allows_multiple_answers' => false,
            ],
        ])->assertOk()->assertExactJson([
            'fallback' => true,
            'type' => 'poll',
        ]);

        $this->assertSame(2003, TelegramWebhookFallbackHandler::$update?->updateId());
    }

    public function test_logs_invalid_dispatcher_handlers_without_payload_values(): void
    {
        $logger = new TelegramWebhookDispatcherTestLogger();
        $this->app->instance(LoggerInterface::class, $logger);

        config()->set('telegram-bot.webhook.handlers.message', ['not-callable']);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2004,
            'message' => [
                'message_id' => 12,
                'text' => 'Private payload',
            ],
        ])->assertOk()->assertExactJson(['ok' => true]);

        $this->assertSame('warning', $logger->records[0]['level']);
        $this->assertSame('Telegram webhook dispatcher handler is configured but is not resolvable or callable.', $logger->records[0]['message']);
        $this->assertSame(2004, $logger->records[0]['context']['update_id']);
        $this->assertSame('message', $logger->records[0]['context']['update_type']);
        $this->assertStringNotContainsString('Private payload', json_encode($logger->records[0], JSON_THROW_ON_ERROR));
    }

    public function test_webhook_middleware_runs_around_configured_handlers(): void
    {
        config()->set('telegram-bot.webhook.middleware', [TelegramWebhookRecordingMiddleware::class]);
        config()->set('telegram-bot.webhook.handlers.message', TelegramWebhookMessageUpdateHandler::class);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2005,
            'message' => [
                'message_id' => 13,
                'text' => 'Middleware',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertExactJson([
            'message_id' => 13,
            'text' => 'Middleware',
            'type' => 'message',
        ]);

        $this->assertSame(['before:2005:default', 'after:2005:default'], TelegramWebhookRecordingMiddleware::$events);
    }

    public function test_webhook_middleware_can_short_circuit_handler_execution(): void
    {
        config()->set('telegram-bot.webhook.middleware', [TelegramWebhookShortCircuitMiddleware::class]);
        config()->set('telegram-bot.webhook.handlers.message', TelegramWebhookMessageUpdateHandler::class);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2006,
            'message' => [
                'message_id' => 14,
                'text' => 'Stop',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertExactJson([
            'middleware' => 'stopped',
            'update_id' => 2006,
        ]);

        $this->assertNull(TelegramWebhookMessageUpdateHandler::$update);
    }

    public function test_route_level_command_middleware_runs_around_command_handler(): void
    {
        config()->set('telegram-bot.webhook.commands.start', [
            'handler' => TelegramWebhookStartCommandHandler::class,
            'middleware' => [TelegramWebhookRecordingMiddleware::class],
        ]);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2007,
            'message' => [
                'message_id' => 15,
                'text' => '/start',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertJson([
            'command' => 'start',
        ]);

        $this->assertSame(['before:2007:default', 'after:2007:default'], TelegramWebhookRecordingMiddleware::$events);
        $this->assertSame(2007, TelegramWebhookStartCommandHandler::$update?->updateId());
    }

    public function test_grouped_update_handlers_inherit_group_middleware(): void
    {
        config()->set('telegram-bot.webhook.groups.admin', [
            'middleware' => [TelegramWebhookRecordingMiddleware::class],
            'handlers' => [
                'message' => TelegramWebhookMessageUpdateHandler::class,
            ],
        ]);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2008,
            'message' => [
                'message_id' => 16,
                'text' => 'Grouped',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertJson([
            'type' => 'message',
            'text' => 'Grouped',
        ]);

        $this->assertSame(['before:2008:default', 'after:2008:default'], TelegramWebhookRecordingMiddleware::$events);
    }

    public function test_fallback_handlers_can_be_scoped_by_update_type(): void
    {
        config()->set('telegram-bot.webhook.fallback_handlers.message', TelegramWebhookFallbackHandler::class);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2009,
            'message' => [
                'message_id' => 17,
                'text' => 'Unknown',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertExactJson([
            'fallback' => true,
            'type' => 'message',
        ]);
    }

    public function test_attribute_discovery_can_register_update_handlers(): void
    {
        config()->set('telegram-bot.webhook.discover.handlers', [
            TelegramWebhookDiscoveredMessageHandler::class,
        ]);

        $this->postJson('/telegram-bot/webhook', [
            'update_id' => 2010,
            'message' => [
                'message_id' => 18,
                'text' => 'Discovered',
                'chat' => ['id' => 456, 'type' => 'private'],
            ],
        ])->assertOk()->assertExactJson([
            'discovered' => true,
            'text' => 'Discovered',
        ]);

        $this->assertSame(['before:2010:default', 'after:2010:default'], TelegramWebhookRecordingMiddleware::$events);
    }
}

final class TelegramWebhookStartCommandHandler implements TelegramWebhookCommandHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static ?TelegramWebhookCommand $command = null;

    public static function reset(): void
    {
        self::$update = null;
        self::$command = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function handle(TelegramWebhookCommand $command, TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;
        self::$command = $command;

        return [
            'bot' => $botName,
            'command' => $command->name(),
            'arguments' => $command->arguments(),
            'message_id' => $command->message()->messageId(),
        ];
    }
}

final class TelegramWebhookMessageUpdateHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static function reset(): void
    {
        self::$update = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function handle(TelegramWebhookUpdate $update, string $_botName): array
    {
        self::$update = $update;

        return [
            'type' => $update->type(),
            'message_id' => $update->effectiveMessage()?->messageId(),
            'text' => $update->effectiveMessage()?->text(),
        ];
    }
}

final class TelegramWebhookFallbackHandler implements TelegramWebhookHandler
{
    public static ?TelegramWebhookUpdate $update = null;

    public static function reset(): void
    {
        self::$update = null;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        self::$update = $update;

        return [
            'fallback' => true,
            'type' => $update->type(),
        ];
    }
}

final class TelegramWebhookDispatcherTestLogger extends AbstractLogger
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

final class TelegramWebhookRecordingMiddleware implements TelegramWebhookMiddleware
{
    /**
     * @var list<string>
     */
    public static array $events = [];

    public static function reset(): void
    {
        self::$events = [];
    }

    #[Override]
    public function process(TelegramWebhookUpdate $update, string $botName, Closure $next): mixed
    {
        self::$events[] = 'before:'.$update->updateId().':'.$botName;
        $result = $next($update, $botName);
        self::$events[] = 'after:'.$update->updateId().':'.$botName;

        return $result;
    }
}

final class TelegramWebhookShortCircuitMiddleware implements TelegramWebhookMiddleware
{
    public static function reset(): void
    {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function process(TelegramWebhookUpdate $update, string $botName, Closure $next): array
    {
        return [
            'middleware' => 'stopped',
            'update_id' => $update->updateId(),
        ];
    }
}

#[TelegramUpdateHandler('message', middleware: [TelegramWebhookRecordingMiddleware::class])]
final class TelegramWebhookDiscoveredMessageHandler implements TelegramWebhookHandler
{
    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): array
    {
        return [
            'discovered' => true,
            'text' => $update->effectiveMessage()?->text(),
        ];
    }
}
