# Laravel Telegram Bot

[![Tests](https://github.com/AlexItDev91/Laravel-Telegram-Bot/actions/workflows/tests.yml/badge.svg)](https://github.com/AlexItDev91/Laravel-Telegram-Bot/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/alexitdev91/laravel-telegram-bot?label=stable)](https://packagist.org/packages/alexitdev91/laravel-telegram-bot)
[![Total Downloads](https://img.shields.io/packagist/dt/alexitdev91/laravel-telegram-bot)](https://packagist.org/packages/alexitdev91/laravel-telegram-bot)
[![License](https://img.shields.io/packagist/l/alexitdev91/laravel-telegram-bot)](LICENSE)
[![PHP Version Require](https://img.shields.io/packagist/php-v/alexitdev91/laravel-telegram-bot)](composer.json)

![Laravel Telegram Bot package cover](docs/assets/package-cover.png)

Laravel-friendly PHP SDK for the official Telegram Bot API.

Developed by Aptenova as an independent open-source package. The package is not tied to any Aptenova application and can be used in any compatible Laravel 12 or 13 project.

## Requirements

- PHP `^8.2`
- PHP extension `openssl`
- Laravel `^12.0|^13.0`
- Guzzle `^7.8`

## Documentation

The package targets Telegram Bot API `10.0`, released on `2026-05-08`.

Read the published documentation:

- [Overview](https://alexitdev91.github.io/Laravel-Telegram-Bot/overview.html)
- [Installation](https://alexitdev91.github.io/Laravel-Telegram-Bot/installation.html)
- [Configuration](https://alexitdev91.github.io/Laravel-Telegram-Bot/configuration.html)
- [Usage](https://alexitdev91.github.io/Laravel-Telegram-Bot/usage.html)
- [End-To-End Setup Guide](https://alexitdev91.github.io/Laravel-Telegram-Bot/telegram-setup.html)
- [Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html)
- [Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html)
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

Laravel 12 and 13 discover the service provider and facade automatically through package discovery.

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

The raw `call(method, parameters)` API remains available for newly released Telegram methods before the typed SDK surface is updated.

## Validation And DTOs

The package keeps the full Telegram Bot API available through native methods and the raw `call()` API. Typed DTOs are provided for the higher-risk Laravel workflows where structured payloads improve correctness: Payments, Stars, paid media, Telegram Passport, Games, package config, channels, and webhook updates.

Typed DTOs validate required fields, empty lists, selected numeric constraints, and documented Telegram conditions before the HTTP request is sent. DTO `extra` arrays are reserved for additional Telegram fields and may not duplicate typed constructor fields. Laravel channel config requires a non-empty `chat_id`; bot config requires a valid `api_url` and positive `timeout`. Generic array calls remain intentionally flexible for newly released Telegram methods and less common API objects.

## Webhooks

The package includes a Laravel webhook receiver at `POST /telegram-bot/webhook` by default. It validates `X-Telegram-Bot-Api-Secret-Token` when `TELEGRAM_WEBHOOK_SECRET_TOKEN` is configured, dispatches a `TelegramWebhookReceived` event, and can call a configured `TelegramWebhookHandler`. For larger bots, configure command handlers, update-type handlers, and a fallback handler through the built-in webhook dispatcher. In production, `TELEGRAM_WEBHOOK_REQUIRE_SECRET` defaults to `true`, so missing webhook secrets fail closed.

For production bots that do non-trivial work in handlers, enable the built-in Laravel queue handoff and duplicate-update guard:

```env
TELEGRAM_WEBHOOK_QUEUE_ENABLED=true
TELEGRAM_WEBHOOK_QUEUE_CONNECTION=redis
TELEGRAM_WEBHOOK_QUEUE=telegram-webhooks
TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED=true
TELEGRAM_WEBHOOK_IDEMPOTENCY_TTL=86400
```

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

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => ['message', 'callback_query'],
]);
```

See [Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html) for the full setup and handler examples.

The package also provides Artisan commands for bot identity checks, deployment diagnostics, delivery test messages, webhook registration, deletion, status checks, and parsed `chat_id` / `message_thread_id` discovery. See [Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html).

## Logging

Laravel integrations log webhook security rejections, invalid webhook payloads, invalid handler configuration, handler failures, Telegram API failures, and transport-level response failures when `TELEGRAM_BOT_LOGGING_ENABLED` is true. Logs include method names, status/error codes, update IDs, update types, and exception classes, but do not include bot tokens, secret headers, request payloads, response bodies, chat IDs, or message text.

`TelegramBotApiException` exposes Telegram response parameters through `parameters()`, `retryAfter()`, and `migrateToChatId()` for rate-limit and group-upgrade recovery paths.

## Files And HTTP Client

Use `InputFile::fromPath()` for uploads. Nested media arrays are converted to Telegram `attach://` multipart references automatically:

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
