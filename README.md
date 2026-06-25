# Laravel Telegram Bot

[![Tests](https://github.com/AlexItDev91/Laravel-Telegram-Bot/actions/workflows/tests.yml/badge.svg)](https://github.com/AlexItDev91/Laravel-Telegram-Bot/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/alexitdev91/laravel-telegram-bot?label=stable)](https://packagist.org/packages/alexitdev91/laravel-telegram-bot)
[![Total Downloads](https://img.shields.io/packagist/dt/alexitdev91/laravel-telegram-bot)](https://packagist.org/packages/alexitdev91/laravel-telegram-bot)
[![License](https://img.shields.io/packagist/l/alexitdev91/laravel-telegram-bot)](LICENSE)
[![PHP Version Require](https://img.shields.io/packagist/php-v/alexitdev91/laravel-telegram-bot)](composer.json)

![Laravel Telegram Bot package cover](docs/assets/package-cover.png)

Laravel-friendly PHP SDK for the official Telegram Bot API.

Developed by Aptenova as an independent open-source package. The package is not tied to any Aptenova application and can be used in any compatible Laravel 13 project.

## Requirements

- PHP `^8.4`
- PHP extension `openssl`
- Guzzle `^7.8`
- Laravel integration: Laravel `^13.0` or matching Illuminate `^13.0` components in the host app

## Version Support Policy

The first major version line is closed. Version `v1.19.1` is the final 1.x release for older host applications.

| Package line | PHP | Laravel integration | Status |
| --- | --- | --- | --- |
| `2.x` | `^8.4` | `^13.0` | Current line. New features, fixes, and Telegram API updates land here. |
| `1.x` | `^8.2` | `^12.0` or `^13.0` | Legacy ceiling. Pin to `^1.19.1` only if the host app cannot move to PHP 8.4 and Laravel 13 yet. |

Starting with `v2.0.0`, this package no longer supports Laravel 12, PHP 8.2, or PHP 8.3.

The `2.x` source uses PHP 8.4-era strictness, including typed class constants, imported `#[Override]` attributes, and PHP 8.4 array helpers such as `array_any`, `array_all`, and `array_find`, so PHP 8.4 is a hard runtime requirement.

## Documentation

The package targets Telegram Bot API `10.1`, released on `2026-06-11`.

Read the published documentation:

- [Overview](https://alexitdev91.github.io/Laravel-Telegram-Bot/overview.html)
- [Installation](https://alexitdev91.github.io/Laravel-Telegram-Bot/installation.html)
- [Configuration](https://alexitdev91.github.io/Laravel-Telegram-Bot/configuration.html)
- [Usage](https://alexitdev91.github.io/Laravel-Telegram-Bot/usage.html)
- [Mini Apps](https://alexitdev91.github.io/Laravel-Telegram-Bot/mini-apps.html)
- [Deep Links](https://alexitdev91.github.io/Laravel-Telegram-Bot/deep-links.html)
- [End-To-End Setup Guide](https://alexitdev91.github.io/Laravel-Telegram-Bot/telegram-setup.html)
- [Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html)
- [Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html)
- [Notifications](https://alexitdev91.github.io/Laravel-Telegram-Bot/notifications.html)
- [Typed Responses](https://alexitdev91.github.io/Laravel-Telegram-Bot/typed-responses.html)
- [Production Recipes](https://alexitdev91.github.io/Laravel-Telegram-Bot/production-recipes.html)
- [Files And HTTP](https://alexitdev91.github.io/Laravel-Telegram-Bot/files-and-http.html)
- [Payments, Passport, And Games](https://alexitdev91.github.io/Laravel-Telegram-Bot/payments-passport-games.html)
- [API Method Support](https://alexitdev91.github.io/Laravel-Telegram-Bot/api-surface.html)
- [API Method Reference](https://alexitdev91.github.io/Laravel-Telegram-Bot/method-reference.html)
- [Troubleshooting](https://alexitdev91.github.io/Laravel-Telegram-Bot/troubleshooting.html)
- [Maintenance](https://alexitdev91.github.io/Laravel-Telegram-Bot/maintenance.html)

Primary sources:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Telegram Bot API changelog](https://core.telegram.org/bots/api-changelog)

## Installation

```bash
composer require alexitdev91/laravel-telegram-bot
```

The core `TelegramBotClient` can be constructed directly in non-Laravel PHP code. In Laravel 13 applications, package discovery registers the service provider and facade automatically.

Publish the Laravel package configuration with the provider-qualified command:

```bash
php artisan vendor:publish --provider="AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider" --tag=telegram-bot-config
```

This creates `config/telegram-bot.php`.

You can also use the interactive package installer:

```bash
php artisan telegram-bot:install
```

If package discovery is disabled in your application, register the provider manually in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider::class,
];
```

Set at least one bot token in environment variables or in `config/telegram-bot.php`.
Keep tokens, chat IDs, and webhook secrets outside committed files.

## Laravel Boost

The package ships Laravel Boost resources that are discovered from the host Laravel application when this package is installed as a dependency:

- `resources/boost/guidelines/core.blade.php`
- `resources/boost/skills/telegram-bot-package/SKILL.md`

Do not run Boost inside this package. Install the package in a Laravel application, then run Boost in that Laravel application so it scans installed packages and adds these resources to the generated agent instructions:

```bash
php artisan boost:install
```

If Boost is already installed and you only need to refresh generated agent resources, run:

```bash
php artisan boost:update
```

## Usage

Use constructor injection in Laravel services, controllers, jobs, listeners, and commands:

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final class SendTelegramAlert
{
    public function __construct(
        private TelegramBot $telegram,
    ) {
    }

    public function __invoke(): void
    {
        $this->telegram->channel('inbox')->sendMessage([
            'text' => 'New inbound email',
        ]);
    }
}
```

You may also depend on `AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager` when you prefer a contract type hint.
Use the concrete `TelegramBot` or `TelegramBotClient` type when you want IDE autocomplete for every native Telegram helper method; the contracts expose the stable core `bot()`, `channel()`, and `call()` surface.

The facade remains available:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('support')->sendMessage([
    'chat_id' => '-1001234567890',
    'text' => 'New message',
]);

TelegramBot::channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);
```

For tenant-owned bots, user-selected destinations, or one-off sends, pass the token and `chat_id` at the call site instead of adding global config:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

$token = $tenant->telegram_bot_token;
$chatId = (string) $tenant->telegram_channel_id;

TelegramBot::to($chatId, token: $token)->sendMessage([
    'text' => 'Tenant alert',
]);
```

The same dynamic path works through constructor injection:

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final readonly class TenantAlert
{
    public function __construct(private TelegramBot $telegram)
    {
    }

    public function send(Tenant $tenant): mixed
    {
        return $this->telegram->to(
            chatId: (string) $tenant->telegram_channel_id,
            token: $tenant->telegram_bot_token,
        )->sendMessage([
            'text' => 'Tenant alert',
        ]);
    }
}
```

Use `botToken($token)` when you want to build the full Telegram payload yourself, or override only the bot used by a configured channel:

```php
TelegramBot::botToken($token)->sendMessage([
    'chat_id' => $chatId,
    'text' => 'Direct dynamic payload',
]);

TelegramBot::channel('inbox', token: $token)->sendMessage([
    'text' => 'Configured destination, dynamic bot',
]);
```

The raw `call(method, parameters)` API remains available for newly released Telegram methods before the typed SDK surface is updated.

Use shortcut methods for the most common outbound messages when you do not need to customize the builder:

```php
TelegramBot::channel('alerts')->text('Deploy finished');

TelegramBot::to('-1001234567890', token: $tenantBotToken)
    ->photo('photo-file-id', caption: 'Daily report');

TelegramBot::document('document-file-id', caption: 'Invoice', to: '-1001234567890');
```

Use the fluent `TelegramMessage` builder when the send flow is common and the destination is explicit:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;

final class TelegramOpsAlert
{
    private const string TEXT_DEPLOY_FINISHED = 'Deploy finished';

    private const string PHOTO_FILE_ID = 'photo-file-id';

    private const string DOCUMENT_FILE_ID = 'document-file-id';

    public function send(string $tenantBotToken): void
    {
        TelegramBot::channel('alerts')->send(
            TelegramMessage::text(self::TEXT_DEPLOY_FINISHED),
        );

        TelegramBot::to('-1001234567890', token: $tenantBotToken)->send(
            TelegramMessage::photo(self::PHOTO_FILE_ID)->caption('Daily report'),
        );

        TelegramBot::botToken($tenantBotToken)->send(
            TelegramMessage::document(self::DOCUMENT_FILE_ID)
                ->to('-1001234567890')
                ->caption('Invoice'),
        );
    }
}
```

The builder supports `text()`, `photo()`, `document()`, `to()`, `caption()`, `parseMode()`, `replyMarkup()`, `silent()`, `protectContent()`, and `extra()` for less common fields.
Use typed request DTOs or raw arrays when you need stricter method-specific validation or the full Telegram surface.

Use typed response helpers when application code needs stable DTO accessors for common Telegram results:

```php
$user = TelegramBot::getMeData();
$message = TelegramBot::channel('alerts')->sendMessageData([
    'text' => 'Deploy finished',
]);

$messageId = $message->messageId();
$chatId = $message->chat()?->id();
```

When `callData()` does not have a dedicated result DTO yet, associative Telegram objects are wrapped in `TelegramBotResultData` so application code can still use typed `get()`, `string()`, `int()`, `bool()`, and `array()` accessors without losing the raw payload.

See [Typed Responses](https://alexitdev91.github.io/Laravel-Telegram-Bot/typed-responses.html) for `callData()`, `TelegramBotResultData`, `getUpdatesData()`, `getFileData()`, `getWebhookInfoData()`, message DTO helpers, and fake-based tests.

## Mini Apps

Validate `Telegram.WebApp.initData` on the server before trusting Mini App user, chat, or start payloads:

```php
use AlexItDev91\LaravelTelegramBot\MiniApps\TelegramMiniAppInitDataValidator;
use Illuminate\Http\Request;

final readonly class TelegramMiniAppSessionController
{
    public function __construct(
        private TelegramMiniAppInitDataValidator $telegramMiniApps,
    ) {
    }

    public function __invoke(Request $request): array
    {
        $data = $this->telegramMiniApps->validate(
            initData: (string) $request->string('initData'),
            botToken: (string) config('telegram-bot.token'),
            maxAgeSeconds: 300,
        );

        return [
            'telegram_user_id' => (string) $data->user()?->id(),
            'start_param' => $data->startParam(),
        ];
    }
}
```

The same `TelegramMiniAppInitDataValidator` works outside Laravel and with runtime tenant bot tokens.
See [Mini Apps](https://alexitdev91.github.io/Laravel-Telegram-Bot/mini-apps.html) for HMAC validation, freshness checks, and typed accessors.

## Deep Links

Generate Telegram `/start`, group install, Mini App, and attachment-menu links without hand-building URLs:

```php
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramDeepLink;
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramStartPayloadSigner;

final readonly class ReferralLinkFactory
{
    public function __construct(
        private TelegramStartPayloadSigner $payloads,
    ) {
    }

    public function link(string $referralCode): string
    {
        $payload = $this->payloads->sign(
            payload: $referralCode,
            secret: (string) config('app.key'),
            ttlSeconds: 3600,
        );

        return TelegramDeepLink::start('CompanyBot', $payload)->url();
    }
}
```

Use `TelegramStartPayloadSigner::verify()` in `/start` command handlers or Mini App controllers before trusting signed `start`, `startgroup`, `startapp`, or `startattach` values.
See [Deep Links](https://alexitdev91.github.io/Laravel-Telegram-Bot/deep-links.html) for payload limits, Mini App links, attachment-menu links, and TTL examples.

## Notifications

Use the Laravel notification channel when Telegram delivery belongs to a notifiable model or on-demand notification:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class DeployFinished extends Notification
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

Routes may return a plain `chat_id`, a configured package `channel`, a named `bot`, a dynamic `token`, and optional `message_thread_id` or `direct_messages_topic_id`.
See [Notifications](https://alexitdev91.github.io/Laravel-Telegram-Bot/notifications.html) for model routing, on-demand routes, typed DTO payloads, and fake-based tests.

## Validation And DTOs

The package keeps the full Telegram Bot API available through native methods and the raw `call()` API. Typed DTOs are provided for the higher-risk Laravel workflows where structured payloads improve correctness: common outbound messages, Business connections, managed bots, Payments, Stars, paid media, suggested posts, guest replies, Telegram Passport, Games, package config, channels, and webhook updates.

Typed DTOs validate required fields, empty lists, selected numeric constraints, and documented Telegram conditions before the HTTP request is sent. DTO `extra` arrays are reserved for additional Telegram fields and may not duplicate typed constructor fields. Laravel channel config requires a non-empty `chat_id`; bot config requires a valid `api_url` and positive `timeout`. Generic array calls remain intentionally flexible for newly released Telegram methods and less common API objects.

Common outbound DTOs include `SendMessageData`, `EditMessageTextData`, `SendPhotoData`, `SendDocumentData`, and `AnswerCallbackQueryData`.
Nested input DTOs are available for common structured payloads such as `LinkPreviewOptions`, `ReplyParameters`, `SuggestedPostParameters`, `SuggestedPostPrice`, `InlineKeyboardButton`, and `InlineKeyboardMarkup`.

```php
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\LinkPreviewOptions;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Support\TelegramCallbackData;

final class DeployAlert
{
    private const string BOT = 'support';

    private const string BUTTON_RETRY = 'Retry';

    private const string BUTTON_OPEN = 'Open run';

    private const string CALLBACK_RETRY = 'deploy:retry';

    private const string CHAT_ID = '-1001234567890';

    private const int RUN_ID = 42;

    private const string RUN_URL = 'https://example.test/runs/42';

    private const string TEXT = 'Build failed';

    public function send(): mixed
    {
        return TelegramBot::bot(self::BOT)->sendMessage(new SendMessageData(
            chatId: self::CHAT_ID,
            text: self::TEXT,
            linkPreviewOptions: LinkPreviewOptions::disabled(),
            replyMarkup: InlineKeyboardMarkup::make()
                ->callback(self::BUTTON_RETRY, TelegramCallbackData::action(self::CALLBACK_RETRY)->with('run', self::RUN_ID))
                ->url(self::BUTTON_OPEN, self::RUN_URL),
        ));
    }
}
```

Generated request builders also bind well-known Telegram string domains to enums, including `TelegramParseMode`, `TelegramChatAction`, `TelegramPollType`, `TelegramStickerType`, `TelegramStickerFormat`, and `TelegramUpdateType`.

For less common methods, use `TelegramBotRequestData::forMethod()` to create a method-scoped DTO backed by the generated `TelegramBotApiMethodSchema`. The schema currently covers all 180 Bot API 10.1 methods and 884 documented parameters, validates required parameters, and prevents a request DTO for one method from being passed to another method. Pass `validateRequiredParameters: false` when a Laravel channel supplies required defaults such as `chat_id`.

Rich messages use the generated Bot API request DTOs with `InputRichMessage` helpers. The package validates that exactly one HTML or Markdown representation is sent:

```php
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendRichMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\InputRichMessage;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('support')->sendRichMessage(SendRichMessageRequestData::make(
    chatId: '-1001234567890',
    richMessage: InputRichMessage::html('<h1>Deploy</h1><p>Finished</p>')
        ->skipEntityDetection(),
));
```

Laravel hosts can inject `AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotLaravelConfig` when application code needs typed access to the configured default bot, named bots, channels, webhook route, and webhook secret validation state. The `telegram-bot:doctor` command uses the same accessor for local configuration checks before live Telegram calls.

## Webhooks

The package includes a Laravel webhook receiver at `POST /telegram-bot/webhook` by default. It validates `X-Telegram-Bot-Api-Secret-Token` when `TELEGRAM_WEBHOOK_SECRET_TOKEN` is configured, dispatches a `TelegramWebhookReceived` event, and can call a configured `TelegramWebhookHandler`. For larger bots, configure command handlers, update-type handlers, middleware, and a fallback handler through the built-in webhook dispatcher. In production, `TELEGRAM_WEBHOOK_REQUIRE_SECRET` defaults to `true`, so missing webhook secrets fail closed.

For production bots that do non-trivial work in handlers, enable the built-in Laravel queue handoff and duplicate-update guard:

```env
TELEGRAM_WEBHOOK_QUEUE_ENABLED=true
TELEGRAM_WEBHOOK_QUEUE_CONNECTION=redis
TELEGRAM_WEBHOOK_QUEUE=telegram-webhooks
TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED=true
TELEGRAM_WEBHOOK_IDEMPOTENCY_TTL=86400
```

Register `AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware` classes in `telegram-bot.webhook.middleware` when handlers need tenant resolution, authorization, tracing, or conversation state before the dispatcher runs.

Enable the cache-backed conversation layer for stateful webhook flows:

```env
TELEGRAM_CONVERSATION_ENABLED=true
TELEGRAM_CONVERSATION_STORE=redis
TELEGRAM_CONVERSATION_TTL=86400
TELEGRAM_CONVERSATION_KEY_PREFIX=telegram-bot:conversation
```

Resolve `AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager` in handlers to read, write, and clear per-bot conversation state keyed by the effective chat and user.
Use `AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWizard` on top of `workflowForUpdate()` for multi-step forms with prompts, validation callbacks, stored step values, `/cancel`, `/back`, resume behavior, and callback-query button transitions.
Use `AlexItDev91\LaravelTelegramBot\Laravel\Handoff\TelegramHumanHandoff` when automation should pause, notify a private operator chat, and later close or resume the original user workflow without coupling the package to a support-system database.
For scenario-first implementation paths, use the Production Recipes cookbook for operations alerts, ecommerce order updates, support intake, and admin-channel notifications with fake-testing examples.

Webhook handlers receive `TelegramWebhookUpdate`, which keeps the raw payload and exposes typed convenience accessors for common Telegram objects:

```php
$message = $update->effectiveMessage();
$chatId = $update->effectiveChat()?->id();
$user = $update->effectiveUser();
$callbackData = $update->callbackQuery()?->data();
$preCheckoutId = $update->preCheckoutQueryData()?->id();
$inlineQuery = $update->inlineQuery()?->query();
$pollId = $update->poll()?->id();
$boostId = $update->chatBoost()?->boostData()?->boostId();
$text = $message?->text();
$documentName = $message?->documentData()?->fileName();
$paymentCharge = $message?->successfulPaymentData()?->telegramPaymentChargeId();
```

Handlers may also return `AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookReply` for synchronous Telegram webhook replies:

```php
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookReply;

return TelegramWebhookReply::fromUpdate($update)->text('Ready.');
return TelegramWebhookReply::fromUpdate($update)->answerCallback('Saved.');
return TelegramWebhookReply::method('sendChatAction', ['chat_id' => $chatId, 'action' => 'typing']);
```

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => ['message', 'callback_query'],
]);
```

See [Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html) for the full setup and handler examples.

The package also provides Artisan commands for bot identity checks, deployment diagnostics, handler scaffolding, delivery test messages, webhook registration, deletion, status checks, and parsed `chat_id` / `message_thread_id` discovery. Use `php artisan telegram-bot:make-handler StartCommand --command=start` to create a command handler in the host app. See [Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html).

## Logging

Laravel integrations log webhook security rejections, invalid webhook payloads, invalid handler configuration, handler failures, Telegram API failures, and transport-level response failures when `TELEGRAM_BOT_LOGGING_ENABLED` is true. Logs include method names, status/error codes, update IDs, update types, and exception classes, but do not include bot tokens, secret headers, request payloads, response bodies, chat IDs, or message text.

`TelegramBotApiException` exposes Telegram response parameters through `parameters()`, `retryAfter()`, and `migrateToChatId()` for rate-limit and group-upgrade recovery paths.
For reliable outbound queues, combine Laravel unique jobs, `retryAfter()` release handling, `TelegramBotRateLimitException::availableIn()`, SDK `retry` config, local `rate_limit` config scoped by bot, method, business connection, and chat, failed-job visibility, and `TelegramBot::fake()` assertions. See [Production Recipes](https://alexitdev91.github.io/Laravel-Telegram-Bot/production-recipes.html).

## Files And HTTP Client

Use `InputFile::fromPath()`, `fromContents()`, `fromStream()`, or `fromResource()` for uploads. Nested media arrays are converted to Telegram `attach://` multipart references automatically:

```php
use AlexItDev91\LaravelTelegramBot\InputFile;

$telegram->bot('support')->sendMediaGroup([
    'chat_id' => '-1001234567890',
    'media' => [
        [
            'type' => 'photo',
            'media' => InputFile::fromPath(storage_path('app/photo.jpg')),
        ],
    ],
]);

$telegram->bot('support')->sendDocument([
    'chat_id' => '-1001234567890',
    'document' => InputFile::fromContents('generated report', 'report.txt', 'text/plain'),
]);
```

To customize transport in Laravel, bind `GuzzleHttp\ClientInterface` before the bot client is resolved:

```php
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

$this->app->bind(ClientInterface::class, fn (): ClientInterface => new Client([
    'timeout' => 5,
    'http_errors' => false,
]));
```

## Testing

```bash
composer install
composer analyse
composer check:telegram-api-surface
composer test
composer test:coverage-surface
```

`analyse` runs PHPStan over package source and release scripts.
`check:telegram-api-surface` compares the local SDK method surface, documented method parameters, and update-type surface with the current official Telegram Bot API documentation and changelog.
`test:coverage-surface` verifies that every registered Telegram Bot API method is exposed as a native SDK method and calls the matching Telegram endpoint path.

For Laravel application tests, use the facade fake instead of mocking HTTP transport:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

$fake = TelegramBot::fake();

TelegramBot::bot('support')->sendMessage([
    'chat_id' => '123456789',
    'text' => 'Hello support',
]);

$fake->assertCalled('sendMessage', function (array $parameters, string $botName): bool {
    return $botName === 'support'
        && $parameters['text'] === 'Hello support';
});
```

Configured Laravel channels are supported by the fake as well. Channel defaults are merged before the call is recorded:

```php
TelegramBot::channel('alerts')->sendMessage([
    'text' => 'Deploy finished',
]);

$fake->assertSentMessageToChannel('alerts', function (array $parameters): bool {
    return $parameters['chat_id'] === '-1001234567890'
        && $parameters['text'] === 'Deploy finished';
});
```
