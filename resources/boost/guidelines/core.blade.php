# Laravel Telegram Bot

This app uses `alexitdev91/laravel-telegram-bot` for Telegram Bot API calls.

## Essentials

- Laravel 12/13 auto-discovers `AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider`.
- Publish config with:

```bash
php artisan vendor:publish --provider="AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider" --tag=telegram-bot-config
```

- Or use the interactive installer:

```bash
php artisan telegram-bot:install
```

- Config lives in `config/telegram-bot.php`.
- If package discovery is disabled, register the provider in `bootstrap/providers.php`.
- Store tokens, chat IDs, webhook secrets, and private identifiers in `.env` or secret storage. Never commit real credentials.

## Usage

- Prefer constructor injection with `AlexItDev91\LaravelTelegramBot\TelegramBot` or `AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager` in Laravel services, controllers, jobs, listeners, and commands.
- Use `AlexItDev91\LaravelTelegramBot\Facades\TelegramBot` when a facade is appropriate.
- Use concrete `TelegramBot` or `TelegramBotClient` when IDE autocomplete for every native Telegram helper is important; contracts expose the stable core surface.
- Use `TelegramBot::bot('name')` for a named bot.
- Use `TelegramBot::channel('name')` for a configured destination with `chat_id` and optional `message_thread_id` or `direct_messages_topic_id`.
- Use `php artisan telegram-bot:me --bot=default` to verify the configured bot token and Telegram identity.
- Use `php artisan telegram-bot:updates` to discover parsed `chat_id`, `message_thread_id`, and `direct_messages_topic_id` values from Telegram updates.
- Use `php artisan telegram-bot:send-test --channel=name` to verify Laravel can send to the configured chat or topic.
- Use `php artisan telegram-bot:webhook:set`, `telegram-bot:webhook:info`, and `telegram-bot:webhook:delete` for webhook management.
- Use `InputFile::fromPath()` for top-level and nested file uploads; nested media files are converted to Telegram `attach://` multipart references.
- Bind `GuzzleHttp\ClientInterface` in the host app when custom transport, retries, proxy, tracing, or HTTP fakes are needed.
- Use the built-in `POST /telegram-bot/webhook` Laravel receiver for incoming updates when `telegram-bot.webhook.route.enabled` is true.
- Protect webhooks with `TELEGRAM_WEBHOOK_SECRET_TOKEN`; the package validates `X-Telegram-Bot-Api-Secret-Token` and fails closed when `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true`.
- Keep `TELEGRAM_BOT_LOGGING_ENABLED=true` for safe operational warning/error logs without tokens, secret headers, request payloads, response bodies, chat IDs, or message text.
- Handle incoming updates with `AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler` or listen for `AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived`.
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
- Follow the release workflow in `AGENTS.md`.

## Testing

- Package: `composer analyse`, `composer test`, and `composer test:coverage-surface`.
- Laravel app: test provider/facade/config/channel behavior with focused tests.
