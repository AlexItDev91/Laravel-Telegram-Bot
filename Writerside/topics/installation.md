# Installation

Install the package into a Laravel 13 application or a PHP project that uses the core client directly:

```bash
composer require alexitdev91/laravel-telegram-bot
```

Laravel discovers the service provider and facade automatically through package discovery. Non-Laravel code can instantiate `TelegramBotClient::make()` directly.

## Requirements

| Dependency | Constraint |
| --- | --- |
| PHP | `^8.4` |
| Laravel or Illuminate integration components | `^13.0` in the host app when Laravel features are used |
| Guzzle | `^7.8` |
| Laravel Prompts | Required by interactive Laravel console commands |
| PSR logger | `^3.0` |

Version `v1.19.1` is the final 1.x release for older host applications. Starting with `v2.0.0`, the package supports only PHP 8.4 and Laravel 13.

## Publish Configuration

Publish `config/telegram-bot.php`:

```bash
php artisan vendor:publish --provider="AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider" --tag=telegram-bot-config
```

You can also run the interactive installer:

```bash
php artisan telegram-bot:install
```

The installer publishes config, can verify the configured bot through Telegram `getMe`, and prints copy-ready environment values.
It does not write secrets into `.env`.

## Manual Provider Registration

If package discovery is disabled in the host application, register the provider manually in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider::class,
];
```

## Minimal Environment

```text
TELEGRAM_BOT=default
TELEGRAM_BOT_TOKEN=<bot-token-from-botfather>
TELEGRAM_BOT_API_URL=https://api.telegram.org
TELEGRAM_BOT_TIMEOUT=10
```

Never commit real bot tokens, chat IDs, webhook secrets, private keys, or screenshots that contain them.

## Verify Installation

Check that the configured token belongs to the expected bot:

```bash
php artisan telegram-bot:me --bot=default
```

After configuring a channel or explicit destination, send a delivery test:

```bash
php artisan telegram-bot:send-test --channel=inbox
```

For a direct explicit chat test:

```bash
php artisan telegram-bot:send-test \
  --bot=default \
  --chat-id=-1001234567890 \
  --text="Telegram delivery test"
```

## Install In Non-Laravel PHP Code

The package is designed for Laravel, but the core client can be created directly:

```php
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;

$telegram = TelegramBotClient::make(
    token: getenv('TELEGRAM_BOT_TOKEN') ?: null,
);

$me = $telegram->getMe();
```

Laravel-only features such as config publishing, facade access, package discovery, events, routes, and Artisan commands require a Laravel application.
