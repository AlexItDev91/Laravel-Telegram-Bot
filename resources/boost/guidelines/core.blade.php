# Laravel Telegram Bot

This app uses `alexitdev91/laravel-telegram-bot` for Telegram Bot API calls.

## Essentials

- Laravel 12/13 auto-discovers `AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider`.
- Publish config with:

```bash
php artisan vendor:publish --provider="AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider" --tag=telegram-bot-config
```

- Config lives in `config/telegram-bot.php`.
- If package discovery is disabled, register the provider in `bootstrap/providers.php`.
- Store tokens, chat IDs, webhook secrets, and private identifiers in `.env` or secret storage. Never commit real credentials.

## Usage

- Use `AlexItDev91\LaravelTelegramBot\Facades\TelegramBot`.
- Use `TelegramBot::bot('name')` for a named bot.
- Use `TelegramBot::channel('name')` for a configured destination with `chat_id` and optional `message_thread_id`.
- Use `TelegramBot::call('methodName', [...])` for new Telegram methods before typed helpers are updated.
- Keep Telegram IDs as strings or 64-bit safe values.

## API Currency

Before changing Telegram behavior, check:

- https://core.telegram.org/bots/api
- https://core.telegram.org/bots/api-changelog

If Telegram changed the Bot API, update methods, enum values, docs, tests, and integration code together.

## Versioning

- Every package update must bump `VERSION`, update `CHANGELOG.md`, and create a git tag.
- Patch bump for small compatible changes, fixes, docs, tests, and cleanup.
- Minor bump for significant compatible changes, new features, or Telegram API surface expansions.
- Follow `docs/RELEASE.md`.

## Testing

- Package: `composer test` and `composer test:coverage-surface`.
- Laravel app: test provider/facade/config/channel behavior with focused tests.
