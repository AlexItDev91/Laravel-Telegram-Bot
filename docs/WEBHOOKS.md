# Telegram Webhooks

This package supports both sides of Telegram webhooks:

- outgoing Bot API methods such as `setWebhook`, `deleteWebhook`, and `getWebhookInfo`;
- an optional Laravel webhook receiver route for incoming Telegram `Update` payloads.

Primary Telegram references:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [setWebhook](https://core.telegram.org/bots/api#setwebhook)
- [Update](https://core.telegram.org/bots/api#update)

## Configuration

Publish the package config and set the webhook values:

```dotenv
TELEGRAM_WEBHOOK_BOT=default
TELEGRAM_WEBHOOK_SECRET_TOKEN=change-this-secret
TELEGRAM_WEBHOOK_REQUIRE_SECRET=true
TELEGRAM_WEBHOOK_ROUTE_ENABLED=true
TELEGRAM_WEBHOOK_ROUTE_URI=telegram-bot/webhook
TELEGRAM_WEBHOOK_ROUTE_NAME=telegram-bot.webhook
```

`config/telegram-bot.php` contains:

```php
'webhook' => [
    'bot' => env('TELEGRAM_WEBHOOK_BOT', env('TELEGRAM_BOT', 'default')),
    'secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN'),
    'require_secret' => env('TELEGRAM_WEBHOOK_REQUIRE_SECRET', env('APP_ENV') === 'production'),
    'handler' => App\Telegram\TelegramWebhookHandler::class,
    'dispatch_event' => true,
    'route' => [
        'enabled' => env('TELEGRAM_WEBHOOK_ROUTE_ENABLED', true),
        'uri' => env('TELEGRAM_WEBHOOK_ROUTE_URI', 'telegram-bot/webhook'),
        'name' => env('TELEGRAM_WEBHOOK_ROUTE_NAME', 'telegram-bot.webhook'),
        'middleware' => [],
    ],
],
```

The route defaults to `POST /telegram-bot/webhook` and is protected by `X-Telegram-Bot-Api-Secret-Token` when `secret_token` is configured. When `require_secret` is true, the middleware rejects webhook requests if no secret is configured; this defaults to true when `APP_ENV=production`.

## Register The Webhook With Telegram

Call `setWebhook` from a deployment command, Tinker, or your own release automation:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => [
        'message',
        'callback_query',
        'my_chat_member',
    ],
]);
```

The secret token must be 1-256 characters and may contain only `A-Z`, `a-z`, `0-9`, `_`, and `-`.

Use `getWebhookInfo()` to inspect webhook status:

```php
$info = TelegramBot::bot('default')->getWebhookInfo();
```

Use `deleteWebhook()` to switch back to `getUpdates`:

```php
TelegramBot::bot('default')->deleteWebhook([
    'drop_pending_updates' => false,
]);
```

## Handle Incoming Updates

Create a handler class:

```php
namespace App\Telegram;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler as TelegramWebhookHandlerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final readonly class TelegramWebhookHandler implements TelegramWebhookHandlerContract
{
    public function __construct(
        private TelegramBot $telegram,
    ) {
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        if ($update->type() !== 'message') {
            return null;
        }

        $chatId = $update->get('message.chat.id');
        $text = $update->get('message.text');

        if ($chatId !== null && $text === '/start') {
            $this->telegram->bot($botName)->sendMessage([
                'chat_id' => (string) $chatId,
                'text' => 'Ready.',
            ]);
        }

        return ['ok' => true];
    }
}
```

Then register it in `config/telegram-bot.php`:

```php
'handler' => App\Telegram\TelegramWebhookHandler::class,
```

The handler may return:

- `null` or `true`: the package returns `{"ok": true}`;
- an array: the package returns it as JSON;
- a string: the package returns it as a plain response;
- a Symfony/Laravel `Response`: the package returns it directly.

Telegram only requires a successful `2xx` response. Keep webhook handlers fast; dispatch jobs for slow work.

## Events

When `dispatch_event` is true, every valid update dispatches:

```php
AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived
```

Example listener registration:

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Illuminate\Support\Facades\Event;

Event::listen(TelegramWebhookReceived::class, function (TelegramWebhookReceived $event): void {
    $type = $event->update->type();
    $bot = $event->botName;
});
```

Use either a configured handler, events, or both. If both are enabled, the event is dispatched before the handler result is converted into the HTTP response.

## TelegramWebhookUpdate

`TelegramWebhookUpdate` keeps the raw payload and detects all Bot API 10.0 update fields:

```php
$update->updateId();          // 123456
$update->type();              // message, callback_query, guest_message, ...
$update->data();              // payload for the detected type
$update->has('message');      // bool
$update->get('message.text'); // nested access with dot notation
$update->payload();           // full raw update payload
```

Unknown future update fields remain available through `payload()` and `get()` even before the SDK adds first-class awareness.

## Route And Middleware

The default route is registered by the service provider:

```text
POST /telegram-bot/webhook
```

Customize it in config:

```php
'route' => [
    'enabled' => true,
    'uri' => 'integrations/telegram/webhook',
    'name' => 'telegram.webhook',
    'middleware' => ['throttle:telegram-webhook'],
],
```

Package middleware always validates `X-Telegram-Bot-Api-Secret-Token` when `secret_token` is configured. It also fails closed when `require_secret` is true and the secret is missing. Add rate limiting, IP filtering, or observability middleware in the `middleware` array when the host application needs it.

## Security Checklist

- Use HTTPS for public Telegram webhooks.
- Set `TELEGRAM_WEBHOOK_SECRET_TOKEN` and pass the same value to `setWebhook`.
- Keep `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true` in production so a missing secret does not silently expose the route.
- Do not commit real bot tokens, webhook secrets, chat IDs, logs, or payload dumps.
- Avoid long-running work in the webhook request; queue it.
- Use `allowed_updates` in `setWebhook` to reduce unnecessary traffic.
- Use `getWebhookInfo` after deployment to confirm Telegram sees the expected URL and has no delivery errors.
