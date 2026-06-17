# Configuration

The package configuration file is `config/telegram-bot.php`.
Publish it during installation, then store real values in the host application's environment or secret manager.

## Environment Values

| Variable | Purpose |
| --- | --- |
| `TELEGRAM_BOT` | Default bot name. Defaults to `default`. |
| `TELEGRAM_BOT_TOKEN` | BotFather token for the default bot. Required for the default bot. |
| `TELEGRAM_BOT_API_URL` | Telegram API base URL. Defaults to `https://api.telegram.org`. |
| `TELEGRAM_BOT_TIMEOUT` | HTTP timeout in seconds. Defaults to `10`. |
| `TELEGRAM_BOT_LOGGING_ENABLED` | Enables safe package logs. Defaults to `true`. |
| `TELEGRAM_WEBHOOK_BOT` | Bot used by the incoming webhook receiver. |
| `TELEGRAM_WEBHOOK_BOT_USERNAME` | Bot username used to ignore commands addressed to another bot. |
| `TELEGRAM_WEBHOOK_SECRET_TOKEN` | Secret Telegram sends in `X-Telegram-Bot-Api-Secret-Token`. Recommended for webhooks. |
| `TELEGRAM_WEBHOOK_REQUIRE_SECRET` | Fails closed when the webhook secret is missing. Recommended in production. |
| `TELEGRAM_WEBHOOK_QUEUE_ENABLED` | Dispatches accepted webhook updates to Laravel queue jobs. |
| `TELEGRAM_WEBHOOK_QUEUE_CONNECTION` | Queue connection for webhook jobs. |
| `TELEGRAM_WEBHOOK_QUEUE` | Queue name for webhook jobs. |
| `TELEGRAM_WEBHOOK_QUEUE_AFTER_COMMIT` | Dispatches webhook jobs after open database transactions commit. |
| `TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED` | Skips duplicate webhook `update_id` values for the same bot. |
| `TELEGRAM_WEBHOOK_IDEMPOTENCY_STORE` | Cache store used by the duplicate-update guard. |
| `TELEGRAM_WEBHOOK_IDEMPOTENCY_TTL` | Duplicate-update guard TTL in seconds. Defaults to `86400`. |
| `TELEGRAM_CONVERSATION_ENABLED` | Enables cache-backed conversation state for webhook handlers. |
| `TELEGRAM_CONVERSATION_STORE` | Cache store used by conversation state. |
| `TELEGRAM_CONVERSATION_TTL` | Conversation state TTL in seconds. Defaults to `86400`. |
| `TELEGRAM_CONVERSATION_KEY_PREFIX` | Cache key prefix for conversation state. |
| `TELEGRAM_WEBHOOK_ROUTE_ENABLED` | Enables the package route. Defaults to `true`. |
| `TELEGRAM_WEBHOOK_ROUTE_URI` | Defaults to `telegram-bot/webhook`. |
| `TELEGRAM_WEBHOOK_ROUTE_NAME` | Defaults to `telegram-bot.webhook`. |

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
    'middleware' => [
        App\Telegram\Middleware\ResolveTelegramTenant::class,
    ],
    'fallback_handler' => App\Telegram\Handlers\FallbackHandler::class,
    'dispatch_event' => true,
    'queue' => [
        'enabled' => env('TELEGRAM_WEBHOOK_QUEUE_ENABLED', false),
        'connection' => env('TELEGRAM_WEBHOOK_QUEUE_CONNECTION'),
        'queue' => env('TELEGRAM_WEBHOOK_QUEUE'),
        'after_commit' => env('TELEGRAM_WEBHOOK_QUEUE_AFTER_COMMIT', false),
    ],
    'idempotency' => [
        'enabled' => env('TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED', false),
        'store' => env('TELEGRAM_WEBHOOK_IDEMPOTENCY_STORE'),
        'ttl' => env('TELEGRAM_WEBHOOK_IDEMPOTENCY_TTL', 86400),
    ],
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

Use `webhook.middleware` for parsed `TelegramWebhookUpdate` pipeline concerns such as tenant resolution, authorization, tracing, and conversation bootstrap. Keep HTTP concerns such as throttling in `webhook.route.middleware`.

## Conversation Configuration

```php
'conversation' => [
    'enabled' => env('TELEGRAM_CONVERSATION_ENABLED', false),
    'store' => env('TELEGRAM_CONVERSATION_STORE'),
    'ttl' => env('TELEGRAM_CONVERSATION_TTL', 86400),
    'key_prefix' => env('TELEGRAM_CONVERSATION_KEY_PREFIX', 'telegram-bot:conversation'),
],
```

The conversation store is disabled by default. Enable it when webhook handlers need cache-backed state through `TelegramConversationManager`.

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
