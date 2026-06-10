# Laravel Example Recipes

These examples are copy-ready starting points for a host Laravel application.
They are not autoloaded by the package and intentionally do not contain real bot tokens, chat IDs, webhook secrets, or payload dumps.

## Files

- `app/Telegram/Commands/StartCommand.php` handles `/start` through the package webhook dispatcher.
- `app/Telegram/Commands/BuyCommand.php` is a payments stub that sends a Telegram Stars invoice payload.
- `app/Telegram/Handlers/CallbackQueryHandler.php` answers callback queries with a typed outbound DTO.
- `app/Telegram/Handlers/ProfileWizardHandler.php` shows the conversation wizard helper with validation, timeout, cancellation, and reset.
- `app/Telegram/Middleware/EnsureTelegramWebhookEnabled.php` is route-level webhook middleware for v2 dispatcher entries.
- `app/Notifications/TelegramDeployFinished.php` sends a Laravel notification through the package notification channel.
- `app/Jobs/SendTelegramAlert.php` sends through a configured channel and handles Telegram `retry_after` and `migrate_to_chat_id` recovery parameters.
- `app/Listeners/RecordTelegramWebhookMetric.php` shows a low-risk observability listener for package webhook events.
- `routes/telegram.php` registers the webhook route manually when the package auto route is disabled.
- `tests/Feature/TelegramBotExampleTest.php` shows the package fake and Testing DSL 2.0 assertions in a host app.

## Config Sketch

```php
'webhook' => [
    'commands' => [
        'start' => [
            'handler' => App\Telegram\Commands\StartCommand::class,
            'middleware' => [App\Telegram\Middleware\EnsureTelegramWebhookEnabled::class],
        ],
        'buy' => App\Telegram\Commands\BuyCommand::class,
    ],
    'handlers' => [
        'callback_query' => App\Telegram\Handlers\CallbackQueryHandler::class,
        'message' => App\Telegram\Handlers\ProfileWizardHandler::class,
    ],
    'fallback_handlers' => [
        'message' => App\Telegram\Handlers\ProfileWizardHandler::class,
    ],
    'queue' => [
        'enabled' => true,
        'connection' => 'redis',
        'queue' => 'telegram-webhooks',
    ],
    'idempotency' => [
        'enabled' => true,
        'store' => 'redis',
        'ttl' => 86400,
    ],
],
'retry' => [
    'enabled' => true,
    'max_attempts' => 2,
    'sleep' => false,
],
'observability' => [
    'enabled' => true,
],
```
