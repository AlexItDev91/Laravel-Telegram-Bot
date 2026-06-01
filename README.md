# Laravel Telegram Bot

![Laravel Telegram Bot package cover](docs/assets/package-cover.png)

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
- [docs/PAYMENTS_PASSPORT_GAMES.md](docs/PAYMENTS_PASSPORT_GAMES.md) - typed helpers, webhook accessors, and examples for Payments, Telegram Passport, paid media, and Games.
- [docs/SETUP.md](docs/SETUP.md) - setup guide from creating a bot and a channel or group to adding the bot and finding Telegram identifiers.
- [docs/WEBHOOKS.md](docs/WEBHOOKS.md) - Laravel webhook receiver setup, secret-token validation, handlers, events, and route configuration.
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

## Webhooks

The package includes a Laravel webhook receiver at `POST /telegram-bot/webhook` by default. It validates `X-Telegram-Bot-Api-Secret-Token` when `TELEGRAM_WEBHOOK_SECRET_TOKEN` is configured, dispatches a `TelegramWebhookReceived` event, and can call a configured `TelegramWebhookHandler`. In production, `TELEGRAM_WEBHOOK_REQUIRE_SECRET` defaults to `true`, so missing webhook secrets fail closed.

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => ['message', 'callback_query'],
]);
```

See [docs/WEBHOOKS.md](docs/WEBHOOKS.md) for the full setup and handler examples.

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
