---
name: telegram-bot-package
description: Use when installing, configuring, updating, or debugging alexitdev91/laravel-telegram-bot in a Laravel application.
---

# Telegram Bot Package

Use for installing, configuring, updating, or debugging `alexitdev91/laravel-telegram-bot`.

## Install Or Publish Config

```bash
composer require alexitdev91/laravel-telegram-bot
php artisan vendor:publish --provider="AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider" --tag=telegram-bot-config
```

Interactive setup is also available:

```bash
php artisan telegram-bot:install
```

Laravel 12/13 auto-discovers the provider. If discovery is disabled, add this to `bootstrap/providers.php`:

```php
AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider::class,
```

## Configure

```dotenv
TELEGRAM_BOT=default
TELEGRAM_BOT_TOKEN=123456:replace-with-real-token
TELEGRAM_BOT_API_URL=https://api.telegram.org
TELEGRAM_BOT_TIMEOUT=10
TELEGRAM_BOT_LOGGING_ENABLED=true
TELEGRAM_INBOX_CHAT_ID=-1001234567890
TELEGRAM_INBOX_MESSAGE_THREAD_ID=
TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID=
TELEGRAM_WEBHOOK_SECRET_TOKEN=change-this-secret
TELEGRAM_WEBHOOK_REQUIRE_SECRET=true
TELEGRAM_WEBHOOK_ROUTE_URI=telegram-bot/webhook
TELEGRAM_WEBHOOK_BOT_USERNAME=
```

`config/telegram-bot.php`:

```php
'channels' => [
    'inbox' => [
        'bot' => 'default',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_INBOX_MESSAGE_THREAD_ID'),
        'direct_messages_topic_id' => env('TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID'),
    ],
],
```

Keep real tokens, webhook secrets, and private identifiers out of git.

Use `php artisan telegram-bot:updates --bot=default` after sending a test message in the target chat or topic to discover parsed `chat_id`, `message_thread_id`, and `direct_messages_topic_id` values.
Use `php artisan telegram-bot:me --bot=default` to verify the configured bot identity.
Use `php artisan telegram-bot:doctor --bot=default` before deploys to check config, webhook secret policy, route registration, and Telegram API reachability.
Use `php artisan telegram-bot:send-test --channel=inbox` to verify delivery to a configured Laravel channel.

## Use

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot as TelegramBotService;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\InputFile;

public function __construct(
    private TelegramBotService $telegram,
) {
}

$this->telegram->channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);

TelegramBot::channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);

TelegramBot::bot('support')->sendMessage([
    'chat_id' => '-1001234567890',
    'text' => 'New message',
]);

$this->telegram->bot('support')->sendMediaGroup([
    'chat_id' => '-1001234567890',
    'media' => [
        [
            'type' => 'photo',
            'media' => InputFile::fromPath(storage_path('app/photo.jpg')),
        ],
    ],
]);
```

Prefer constructor injection with `AlexItDev91\LaravelTelegramBot\TelegramBot` or `AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager` in Laravel services, controllers, jobs, listeners, and commands. Use concrete `TelegramBot` or `TelegramBotClient` when IDE autocomplete for every native Telegram helper is important. Use the facade where a facade fits the host app style.

Use `InputFile::fromPath()` for top-level and nested file uploads. Nested media files are converted to Telegram `attach://` multipart references automatically.

Bind `GuzzleHttp\ClientInterface` in the host app when custom transport, retries, proxy, tracing, or HTTP fakes are needed. Keep `http_errors` disabled so Telegram API error payloads remain available to the SDK.

## Webhooks

The package registers `POST /telegram-bot/webhook` when `telegram-bot.webhook.route.enabled` is true. Protect it with `TELEGRAM_WEBHOOK_SECRET_TOKEN`; the package validates `X-Telegram-Bot-Api-Secret-Token` and fails closed when `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true`.

Keep `TELEGRAM_BOT_LOGGING_ENABLED=true` for safe operational warning/error logs. Logs must not include bot tokens, secret headers, request payloads, response bodies, chat IDs, or message text.

For production handlers that do real work, set `TELEGRAM_WEBHOOK_QUEUE_ENABLED=true`, run Laravel queue workers for `AlexItDev91\LaravelTelegramBot\Laravel\Jobs\TelegramWebhookJob`, and enable `TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED=true` with a shared cache store when duplicate update processing is unsafe.

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => ['message', 'callback_query'],
]);
```

Webhook management commands:

```bash
php artisan telegram-bot:webhook:set --bot=default --url=https://example.com/telegram-bot/webhook
php artisan telegram-bot:webhook:info --bot=default
php artisan telegram-bot:webhook:delete --bot=default --yes
```

Handle incoming updates with `AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler` or listen for `AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived`. For larger bots, configure `telegram-bot.webhook.commands`, `telegram-bot.webhook.handlers`, and `telegram-bot.webhook.fallback_handler`. Command handlers implement `AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler` and receive `AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookCommand`. Use `Route::telegramBotWebhook()` when manually registering webhook routes. See `docs/WEBHOOKS.md` for the full receiver setup.

Use typed update accessors instead of ad hoc nested arrays when the object is covered:

```php
$message = $update->effectiveMessage();
$chatId = $update->effectiveChat()?->id();
$userId = $update->effectiveUser()?->id();
$callbackData = $update->callbackQuery()?->data();
$preCheckoutId = $update->preCheckoutQueryData()?->id();
$orderEmail = $update->preCheckoutQueryData()?->orderInfoData()?->email();
$pollId = $update->poll()?->id();
$boostId = $update->chatBoost()?->boostData()?->boostId();
$documentName = $message?->documentData()?->fileName();
$paymentCharge = $message?->successfulPaymentData()?->telegramPaymentChargeId();
$newMemberStatus = $update->chatMember()?->newChatMemberData()?->status();
```

Useful typed inbound helpers include:

- `message()`, `editedMessage()`, `channelPost()`, `editedChannelPost()`, `businessMessage()`, `editedBusinessMessage()`, `guestMessage()`
- `effectiveMessage()`, `effectiveChat()`, `effectiveUser()`
- `callbackQuery()`, `inlineQuery()`, `chosenInlineResult()`
- `shippingQueryData()`, `preCheckoutQueryData()`
- `myChatMember()`, `chatMember()`, `chatJoinRequest()`
- `businessConnection()`, `deletedBusinessMessages()`, `purchasedPaidMediaData()`, `poll()`, `pollAnswer()`, `messageReaction()`, `messageReactionCount()`, `chatBoost()`, `removedChatBoost()`, `managedBot()`
- `photoData()`, `documentData()`, `entitiesData()`, `captionEntitiesData()`, `successfulPaymentData()`, `orderInfoData()`, `oldChatMemberData()`, `newChatMemberData()`

Keep the raw payload available through `payload()`, `get()`, and backward-compatible array helpers when Telegram sends fields before the SDK adds typed DTO coverage.

Use `TelegramBot::call('methodName', [...])` for Telegram methods that do not have a typed helper yet. Keep Telegram IDs as strings or 64-bit safe values.

When catching `AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException`, use `retryAfter()` for rate-limit recovery and `migrateToChatId()` for group-to-supergroup migration handling.

## Keep Current

Before changing Telegram API behavior, check both official sources:

- https://core.telegram.org/bots/api
- https://core.telegram.org/bots/api-changelog

When Telegram changes the Bot API, update methods, enum values, docs, tests, and Laravel integration examples together.

## Version

Every package update must bump `VERSION`, update `CHANGELOG.md`, and create a git tag.

- Patch bump: small compatible changes, fixes, docs, tests, cleanup.
- Minor bump: significant compatible changes, new features, Telegram API surface expansions.
- Major bump: breaking public API, config, behavior, namespace, dependency, or Laravel compatibility changes.

Follow the release workflow in `AGENTS.md`.

## Test

```bash
composer analyse
composer test
composer test:coverage-surface
```

In Laravel apps, add focused tests for provider registration, facade resolution, bot/channel config, payloads, and errors.
Use `TelegramBot::fake()`, `assertCalled()`, `assertSentMessage()`, `assertSentMessageToChannel()`, and `assertNothingSent()` to test application code without calling Telegram.
