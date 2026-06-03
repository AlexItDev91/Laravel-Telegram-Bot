# Configuration

The package configuration file is `config/telegram-bot.php`.
Publish it during installation, then store real values in the host application's environment or secret manager.

## Environment Values

| Variable | Required | Purpose |
| --- | --- | --- |
| `TELEGRAM_BOT` | No | Default bot name. Defaults to `default`. |
| `TELEGRAM_BOT_TOKEN` | Yes for the default bot | BotFather token for the default bot. |
| `TELEGRAM_BOT_API_URL` | No | Telegram API base URL. Defaults to `https://api.telegram.org`. |
| `TELEGRAM_BOT_TIMEOUT` | No | HTTP timeout in seconds. Defaults to `10`. |
| `TELEGRAM_BOT_LOGGING_ENABLED` | No | Enables safe package logs. Defaults to `true`. |
| `TELEGRAM_WEBHOOK_BOT` | No | Bot used by the incoming webhook receiver. |
| `TELEGRAM_WEBHOOK_BOT_USERNAME` | No | Bot username used to ignore commands addressed to another bot. |
| `TELEGRAM_WEBHOOK_SECRET_TOKEN` | Recommended for webhooks | Secret Telegram sends in `X-Telegram-Bot-Api-Secret-Token`. |
| `TELEGRAM_WEBHOOK_REQUIRE_SECRET` | Recommended in production | Fails closed when the webhook secret is missing. |
| `TELEGRAM_WEBHOOK_ROUTE_ENABLED` | No | Enables the package route. Defaults to `true`. |
| `TELEGRAM_WEBHOOK_ROUTE_URI` | No | Defaults to `telegram-bot/webhook`. |
| `TELEGRAM_WEBHOOK_ROUTE_NAME` | No | Defaults to `telegram-bot.webhook`. |

## Single Bot

```php
'default' => env('TELEGRAM_BOT', 'default'),

'token' => env('TELEGRAM_BOT_TOKEN'),
'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
```

Use this when the host application has one Telegram bot.

## Multiple Bots

```php
'bots' => [
    'support' => [
        'token' => env('TELEGRAM_SUPPORT_BOT_TOKEN'),
        'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
        'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
    ],
    'shop' => [
        'token' => env('TELEGRAM_SHOP_BOT_TOKEN'),
    ],
],
```

Per-bot values inherit shared defaults when they are omitted or empty.

## Channel Mappings

Channel mappings remove repeated `chat_id`, `message_thread_id`, and `direct_messages_topic_id` values from application code.

```php
'channels' => [
    'inbox' => [
        'bot' => 'support',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
    ],
    'deployments' => [
        'bot' => 'support',
        'chat_id' => env('TELEGRAM_DEPLOYMENTS_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_DEPLOYMENTS_MESSAGE_THREAD_ID'),
    ],
    'direct_messages' => [
        'bot' => 'support',
        'chat_id' => env('TELEGRAM_DM_CHAT_ID'),
        'direct_messages_topic_id' => env('TELEGRAM_DM_TOPIC_ID'),
    ],
],
```

Then send through the configured channel:

```php
$telegram->channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);
```

## Webhook Configuration

```php
'webhook' => [
    'bot' => env('TELEGRAM_WEBHOOK_BOT', env('TELEGRAM_BOT', 'default')),
    'bot_username' => env('TELEGRAM_WEBHOOK_BOT_USERNAME'),
    'secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN'),
    'require_secret' => env('TELEGRAM_WEBHOOK_REQUIRE_SECRET', env('APP_ENV') === 'production'),
    'handler' => App\Telegram\TelegramWebhookHandler::class,
    'handlers' => [
        'message' => App\Telegram\Handlers\MessageHandler::class,
        'callback_query' => App\Telegram\Handlers\CallbackQueryHandler::class,
    ],
    'commands' => [
        'start' => App\Telegram\Commands\StartCommand::class,
    ],
    'fallback_handler' => App\Telegram\Handlers\FallbackHandler::class,
    'dispatch_event' => true,
    'route' => [
        'enabled' => env('TELEGRAM_WEBHOOK_ROUTE_ENABLED', true),
        'uri' => env('TELEGRAM_WEBHOOK_ROUTE_URI', 'telegram-bot/webhook'),
        'name' => env('TELEGRAM_WEBHOOK_ROUTE_NAME', 'telegram-bot.webhook'),
        'middleware' => [],
    ],
],
```

The secret token must be 1-256 characters and use only `A-Z`, `a-z`, `0-9`, `_`, and `-`.
Invalid configured secrets fail closed.

## Logging

When `TELEGRAM_BOT_LOGGING_ENABLED=true`, the Laravel integration logs security rejections, malformed webhook payloads, invalid handler configuration, handler failures, Telegram API failures, and transport failures.

Logs include method names, status/error codes, update IDs, update types, and exception classes.
They do not include bot tokens, secret headers, request payloads, response bodies, chat IDs, or message text.

## Validation Rules

| Config or payload | Validation |
| --- | --- |
| Bot token | Must be configured before API calls. |
| API URL | Must be a valid URL in typed config data. |
| Timeout | Must be positive. |
| Channel `chat_id` | Must be non-empty. |
| Webhook secret | Must match Telegram's documented character contract. |
| Typed DTO `extra` | May not duplicate constructor-backed fields. |
| Generic array calls | Kept flexible for new Telegram fields and methods. |
