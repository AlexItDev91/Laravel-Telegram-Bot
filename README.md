# Laravel Telegram Bot

Laravel-friendly PHP SDK for the official Telegram Bot API.

Developed by Aptenova as an independent open-source package. The package is not tied to any Aptenova application and can be used in any compatible Laravel 12 or 13 project.

## Requirements

- PHP `^8.2`
- Laravel `^12.0|^13.0`
- Guzzle `^7.8`

## API Coverage

The package targets Telegram Bot API `10.0`, released on `2026-05-08`.

Available documentation:

- [docs/API.md](docs/API.md) - supported Telegram Bot API method matrix with links to the official Telegram documentation for every method.
- [docs/METHODS.md](docs/METHODS.md) - full SDK method reference with call signatures, endpoints, and official parameter names/types.
- [docs/SETUP.md](docs/SETUP.md) - setup guide from creating a bot and a channel or group to adding the bot and finding Telegram identifiers.
- [docs/RELEASE.md](docs/RELEASE.md) - release process for version bumps, changelog updates, and git tags.

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

## Testing

```bash
composer install
composer test
composer test:coverage-surface
```

`test:coverage-surface` verifies that every registered Telegram Bot API method is exposed as a native SDK method and calls the matching Telegram endpoint path.
