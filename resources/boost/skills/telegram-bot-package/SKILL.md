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
TELEGRAM_CONVERSATION_ENABLED=false
TELEGRAM_CONVERSATION_STORE=
TELEGRAM_CONVERSATION_TTL=86400
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
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramDeepLink;
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramStartPayloadSigner;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use AlexItDev91\LaravelTelegramBot\MiniApps\TelegramMiniAppInitDataValidator;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
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

TelegramBot::to($chatId, token: $botToken)->sendMessage([
    'text' => 'Runtime bot and destination',
]);

TelegramBot::channel('inbox', token: $botToken)->sendMessage([
    'text' => 'Configured destination, runtime bot',
]);

TelegramBot::channel('inbox')->send(
    TelegramMessage::text('New inbound email'),
);

TelegramBot::to($chatId, token: $botToken)->send(
    TelegramMessage::photo('photo-file-id')->caption('Daily report'),
);

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
Use `to($chatId, token: $botToken)` when the bot token and destination are resolved dynamically at runtime, and keep those values in host app secrets or tenant-owned storage rather than committed package config.
Use `Outbound\TelegramMessage` for simple fluent text/photo/document sends. Use typed request DTOs or raw arrays when method-specific validation or the full Telegram surface is needed.

Use `InputFile::fromPath()` for top-level and nested file uploads. Nested media files are converted to Telegram `attach://` multipart references automatically.

Use typed outbound DTOs for common payloads when validation helps: `AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData`, `EditMessageTextData`, `SendPhotoData`, `SendDocumentData`, and `AnswerCallbackQueryData`.
Prefer nested input DTOs over raw arrays for common structured payloads: `LinkPreviewOptions`, `ReplyParameters`, `SuggestedPostParameters`, `SuggestedPostPrice`, `InlineKeyboardButton`, and `InlineKeyboardMarkup`.
Prefer package enums over magic strings for known Telegram domains: `TelegramParseMode`, `TelegramChatAction`, `TelegramPollType`, `TelegramStickerType`, `TelegramStickerFormat`, and `TelegramUpdateType`.
Inject `TelegramBotLaravelConfig` when a host app needs typed access to bot, channel, webhook route, or webhook secret configuration.

Use typed response helpers when code needs DTO accessors for returned Telegram objects:

```php
$user = TelegramBot::getMeData();
$message = TelegramBot::channel('inbox')->sendMessageData([
    'text' => 'New inbound email',
]);
$webhook = TelegramBot::getWebhookInfoData();
```

Typed response helpers include `callData()`, `getMeData()`, `getChatData()`, `getChatMemberData()`, `getChatAdministratorsData()`, `getFileData()`, `getWebhookInfoData()`, `getUpdatesData()`, `sendMessageData()`, `sendPhotoData()`, `sendDocumentData()`, `forwardMessageData()`, and `editMessageTextData()`. Raw methods still return Telegram's decoded `result` unchanged. When no dedicated result DTO exists, `callData()` wraps associative Telegram objects in `TelegramBotResultData` and lists of objects in `list<TelegramBotResultData>`.

Use `TelegramBotRequestData::forMethod()` for less common Bot API methods that need generated method/required-parameter validation through `TelegramBotApiMethodSchema`. Pass `validateRequiredParameters: false` only when configured channels supply required defaults such as `chat_id` later.

Use `TelegramMiniAppInitDataValidator` to validate raw `Telegram.WebApp.initData` before trusting Mini App user, chat, or start-parameter values:

```php
public function __construct(
    private TelegramMiniAppInitDataValidator $telegramMiniApps,
) {
}

$data = $this->telegramMiniApps->validate(
    initData: (string) $request->string('initData'),
    botToken: (string) config('telegram-bot.token'),
    maxAgeSeconds: 300,
);
```

Pass the tenant-owned runtime bot token when the Mini App belongs to a tenant, and keep the raw `Telegram.WebApp.initData` string instead of sending `initDataUnsafe` to the backend.

Use `TelegramDeepLink` and `TelegramStartPayloadSigner` for signed `/start`, `startgroup`, Mini App `startapp`, and attachment-menu `startattach` links:

```php
public function __construct(
    private TelegramStartPayloadSigner $payloads,
) {
}

$payload = $this->payloads->sign(
    payload: 'ref42',
    secret: (string) config('app.key'),
    ttlSeconds: 3600,
);

$url = TelegramDeepLink::start('CompanyBot', $payload)->url();
```

Verify signed payloads with `$this->payloads->verify($command->arguments(), (string) config('app.key'))` before trusting referral, onboarding, support, or Mini App start parameters.

Use `TelegramBotNotificationChannel` for Laravel notifications:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

final class DeployFinished extends Notification
{
    private const string CHANNEL = 'alerts';

    private const string TEXT = 'Deploy finished';

    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text(self::TEXT)
            ->channel(self::CHANNEL)
            ->parseMode(TelegramParseMode::HTML);
    }
}
```

Notification routes may come from `routeNotificationForTelegram()` or `Notification::route('telegram', [...])`.
Return `channel`, `bot`, `chat_id`, `message_thread_id`, or `direct_messages_topic_id` values from routing code. Keep tokens, chat IDs, and secrets out of notification classes.
`toTelegram()` may return `TelegramNotificationMessage`, `string`, explicit `array` payloads, typed request DTOs, or `null` to skip delivery. Wrap generic `TelegramBotRequestData` in `TelegramNotificationMessage::forMethod()`.

Bind `GuzzleHttp\ClientInterface` in the host app when custom transport, retries, proxy, tracing, or HTTP fakes are needed. Keep `http_errors` disabled so Telegram API error payloads remain available to the SDK.

For reliable outbound delivery, queue messages in Laravel jobs, make duplicate-prone jobs unique by a stable domain key, release jobs on `TelegramBotApiException::retryAfter()` and `TelegramBotRateLimitException::availableIn()`, keep non-retryable failures visible in failed jobs, enable SDK `retry` and local `rate_limit` config for bursty workers, and cover queue paths with `TelegramBot::fake()` plus `assertNoTokenLeakage()`.

For scenario-first builds, start from `docs/RECIPES.md`: operations alerts use configured channels and queued jobs, ecommerce order updates use dynamic `TelegramBot::to($chatId, token: $tenantToken)` routing, support intake combines `TelegramConversationWizard` with `TelegramHumanHandoff`, and admin-channel notifications use configured admin channels plus callback handlers with admin middleware.

## Webhooks

The package registers `POST /telegram-bot/webhook` when `telegram-bot.webhook.route.enabled` is true. Protect it with `TELEGRAM_WEBHOOK_SECRET_TOKEN`; the package validates `X-Telegram-Bot-Api-Secret-Token` and fails closed when `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true`.

Keep `TELEGRAM_BOT_LOGGING_ENABLED=true` for safe operational warning/error logs. Logs must not include bot tokens, secret headers, request payloads, response bodies, chat IDs, or message text.

For production handlers that do real work, set `TELEGRAM_WEBHOOK_QUEUE_ENABLED=true`, run Laravel queue workers for `AlexItDev91\LaravelTelegramBot\Laravel\Jobs\TelegramWebhookJob`, and enable `TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED=true` with a shared cache store when duplicate update processing is unsafe.

Use `telegram-bot.webhook.middleware` with `AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware` for parsed-update pipeline concerns such as tenant resolution, authorization, tracing, and conversation bootstrap. Middleware can short-circuit by returning a response without calling `$next`.

Use `TELEGRAM_CONVERSATION_ENABLED=true` and inject `AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager` for cache-backed stateful webhook flows. Configure `TELEGRAM_CONVERSATION_STORE`, `TELEGRAM_CONVERSATION_TTL`, and `TELEGRAM_CONVERSATION_KEY_PREFIX` in the host app. Prefer `AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWizard` on top of `workflowForUpdate()` for form-style flows that need prompts, validation callbacks, `/cancel`, `/back`, resume behavior, and callback-query button transitions.

Use `AlexItDev91\LaravelTelegramBot\Laravel\Handoff\TelegramHumanHandoff` when automation should pause and a human operator needs a private support chat summary. Store the original workflow key or ticket ID in the host app, queue operator notifications for busy support inboxes, and keep raw customer text, attachments, bot tokens, webhook secrets, and payment data out of handoff summaries unless they are genuinely required.

Observe webhook processing with `TelegramWebhookReceived`, `TelegramWebhookHandled`, `TelegramWebhookFailed`, `TelegramWebhookQueued`, and `TelegramWebhookDuplicateSkipped`. Use `docs/DEEP_LINKS.md`, `docs/MINI_APPS.md`, `docs/RECIPES.md`, `docs/NOTIFICATIONS.md`, `docs/RESPONSES.md`, and `examples/laravel` for copy-ready deep links, Mini Apps validation, notifications, typed response accessors, jobs, handlers, listeners, and route snippets.

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
After refreshing the method reference, run `composer generate:telegram-api-schema` so `TelegramBotApiMethodSchema` and `TelegramBotRequestData::forMethod()` stay aligned with the official Bot API.

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
