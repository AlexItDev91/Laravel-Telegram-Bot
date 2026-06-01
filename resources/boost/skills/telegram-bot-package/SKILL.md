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
TELEGRAM_INBOX_CHAT_ID=-1001234567890
TELEGRAM_INBOX_MESSAGE_THREAD_ID=
```

`config/telegram-bot.php`:

```php
'channels' => [
    'inbox' => [
        'bot' => 'default',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_INBOX_MESSAGE_THREAD_ID'),
    ],
],
```

Keep real tokens, webhook secrets, and private identifiers out of git.

## Use

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot as TelegramBotService;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

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
```

Prefer constructor injection with `AlexItDev91\LaravelTelegramBot\TelegramBot` or `AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager` in Laravel services, controllers, jobs, listeners, and commands. Use the facade where a facade fits the host app style.

Use `TelegramBot::call('methodName', [...])` for Telegram methods that do not have a typed helper yet. Keep Telegram IDs as strings or 64-bit safe values.

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

Follow `docs/RELEASE.md`.

## Test

```bash
composer test
composer test:coverage-surface
```

In Laravel apps, add focused tests for provider registration, facade resolution, bot/channel config, payloads, and errors.
