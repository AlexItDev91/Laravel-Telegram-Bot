# Laravel Example Recipes

These examples are copy-ready starting points for a host Laravel application.
They are not autoloaded by the package and intentionally do not contain real bot tokens, chat IDs, webhook secrets, or payload dumps.

## Files

- `app/Telegram/Commands/StartCommand.php` handles `/start` through the package webhook dispatcher.
- `app/Telegram/Handlers/CallbackQueryHandler.php` answers callback queries with a typed outbound DTO.
- `app/Jobs/SendTelegramAlert.php` sends through a configured channel and handles Telegram `retry_after` and `migrate_to_chat_id` recovery parameters.
- `app/Listeners/RecordTelegramWebhookMetric.php` shows a low-risk observability listener for package webhook events.
- `routes/telegram.php` registers the webhook route manually when the package auto route is disabled.

## Config Sketch

```php
'webhook' => [
    'commands' => [
        'start' => App\Telegram\Commands\StartCommand::class,
    ],
    'handlers' => [
        'callback_query' => App\Telegram\Handlers\CallbackQueryHandler::class,
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
```
