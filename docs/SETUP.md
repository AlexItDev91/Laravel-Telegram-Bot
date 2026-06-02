# Telegram Bot End-To-End Setup Guide

This guide explains the full path from an empty Telegram account to a working Laravel integration:

1. create a bot;
2. get the bot token, bot username, and bot ID;
3. create a channel or group;
4. add the bot to the destination;
5. get the channel, group, or topic identifiers;
6. install and configure the Laravel package;
7. send a safe test message.

Primary Telegram references:

- [Telegram Bots](https://core.telegram.org/bots)
- [Telegram Bot Features](https://core.telegram.org/bots/features)
- [Telegram Bot API](https://core.telegram.org/bots/api)
- [BotFather](https://t.me/BotFather)

## 1. Values You Will Need

Keep these values separate:

| Value | Example | Required by this package | Where it comes from |
| --- | --- | --- | --- |
| Bot token | `123456:ABC...` | Yes | BotFather |
| Bot username | `@CompanyInboxBot` | No, but useful for adding/searching the bot | BotFather |
| Bot ID | `123456` | Usually no | `getMe`, or the number before `:` in the token |
| Destination chat ID | `-1001234567890` | Yes | `getUpdates` or `getChat` |
| Topic ID | `42` | Only for forum topics | `getUpdates` |

Do not commit real tokens, private chat IDs, webhook secrets, or screenshots containing them.

## 2. Create The Bot In BotFather

1. Open Telegram.
2. Search for `@BotFather`.
3. Open the verified BotFather chat.
4. Press `Start`.
5. Send `/newbot`.
6. Enter a display name, for example `Company Inbox`.
7. Enter a username. It must end with `bot`, for example `CompanyInboxBot`.
8. BotFather will send the bot token.
9. Copy the token into a password manager or secret store.

The bot token is the credential your Laravel app uses to call Telegram. Treat it like a password.

## 3. Save Or Regenerate The Bot Token

If you need the token later:

1. Open `@BotFather`.
2. Send `/mybots`.
3. Select the bot.
4. Open `API Token`.
5. Copy the token.

If the token was exposed in a commit, screenshot, ticket, log, or chat, revoke it in BotFather and generate a new token.

## 4. Get The Bot ID And Username

The bot username is the public handle, for example `@CompanyInboxBot`.

The bot ID is the numeric identifier returned by `getMe`. Open this URL in a browser, replacing `<BOT_TOKEN>`:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getMe
```

Example response:

```json
{
  "ok": true,
  "result": {
    "id": 123456,
    "is_bot": true,
    "first_name": "Company Inbox",
    "username": "CompanyInboxBot"
  }
}
```

Use:

- `result.id` as the bot ID;
- `result.username` as the bot username;
- the full token as `TELEGRAM_BOT_TOKEN`.

This package usually does not need the bot ID in config, but it is useful for audits, access checks, and external operations.

## 5. Optional Bot Settings

In BotFather:

1. Send `/mybots`.
2. Select the bot.
3. Open `Edit Bot`.
4. Set the bot picture, description, about text, and commands if needed.

Useful commands for a simple notification bot:

```text
status - Show bot status
help - Show available commands
```

For notification-only posting into a channel, group privacy settings usually do not matter. If the bot must read group messages, review BotFather privacy settings.

## 6. Create A Channel

Use a channel when the bot only needs to post notifications and people do not need threaded discussion.

1. Open Telegram.
2. Press the new message button.
3. Select `New Channel`.
4. Enter a channel name, for example `Company Inbox Alerts`.
5. Add an optional description.
6. Choose `Private Channel` unless the channel must be public.
7. Finish channel creation.

## 7. Add The Bot To The Channel

1. Open the channel.
2. Open the channel profile.
3. Open `Administrators`.
4. Press `Add Admin`.
5. Search for the bot username, for example `@CompanyInboxBot`.
6. Add the bot.
7. Give it the minimum permissions it needs.

For notification-only usage, the bot usually needs permission to post messages. Avoid broad admin permissions unless the bot actually needs them.

## 8. Create A Group Instead Of A Channel

Use a group when people need to reply, discuss, or work around the notification.

1. Open Telegram.
2. Press the new message button.
3. Select `New Group`.
4. Add at least one person or the bot.
5. Enter a group name, for example `Company Inbox`.
6. Open the group profile.
7. Add the bot if it was not added during creation.
8. Configure bot permissions.

If the group has forum topics enabled, each topic has its own `message_thread_id`.

## 9. Trigger A Telegram Update

Before looking for chat IDs:

1. Make sure the bot is in the channel or group.
2. Send a fresh test message in the exact destination.
3. If using a forum group, send the message inside the exact topic.

For channels, the bot normally needs to be an administrator to receive channel post updates and post messages.

## 10. Get The Channel Or Group ID With getUpdates

Open this URL in a browser:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getUpdates
```

Look for one of these objects:

- `message.chat` for group/private messages;
- `channel_post.chat` for channel posts.

Example channel response:

```json
{
  "ok": true,
  "result": [
    {
      "update_id": 100000001,
      "channel_post": {
        "message_id": 5,
        "chat": {
          "id": -1001234567890,
          "title": "Company Inbox Alerts",
          "type": "channel"
        },
        "text": "Setup test"
      }
    }
  ]
}
```

Use the full `chat.id` value as `TELEGRAM_INBOX_CHAT_ID`, including the minus sign.

## 11. Get A Channel ID With getChat

If `getUpdates` is empty, use `getChat`.

For a public channel:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getChat?chat_id=@company_inbox_alerts
```

For a private channel:

1. Open channel settings.
2. Set a temporary public username, for example `company_inbox_alerts_temp`.
3. Open:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getChat?chat_id=@company_inbox_alerts_temp
```

4. Copy `result.id`.
5. Remove the temporary public username if the channel should stay private.

Example response:

```json
{
  "ok": true,
  "result": {
    "id": -1001234567890,
    "title": "Company Inbox Alerts",
    "type": "channel"
  }
}
```

Use `result.id` as `TELEGRAM_INBOX_CHAT_ID`.

## 12. Get A Topic ID

Forum groups use `message_thread_id` to identify topics.

1. Enable topics in the group if needed.
2. Open the exact topic.
3. Send a test message in that topic.
4. Open:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getUpdates
```

5. Find `message_thread_id` in the message object.
6. Use that value as `TELEGRAM_INBOX_MESSAGE_THREAD_ID`.

Example:

```json
{
  "message": {
    "message_id": 10,
    "message_thread_id": 42,
    "chat": {
      "id": -1001234567890,
      "type": "supergroup"
    },
    "text": "Topic setup test"
  }
}
```

If `message_thread_id` is absent, the message was not sent inside a forum topic.

The package can also fetch updates and print parsed identifiers for you:

```bash
php artisan telegram-bot:updates --bot=default
```

See [docs/CONSOLE_COMMANDS.md](CONSOLE_COMMANDS.md) for parsed `chat_id`, `message_thread_id`, and webhook command examples.

## 13. Install The Laravel Package

Install the package in the host Laravel application:

```bash
composer require alexitdev91/laravel-telegram-bot
```

Laravel 12 and 13 discover the service provider and facade automatically.

Publish the package configuration:

```bash
php artisan vendor:publish --provider="AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider" --tag=telegram-bot-config
```

This creates:

```text
config/telegram-bot.php
```

You can also run the package installer, which uses Laravel Prompts and prints copy-ready env/config snippets:

```bash
php artisan telegram-bot:install
```

If package discovery is disabled, register the provider manually in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider::class,
];
```

## 14. Configure Environment Values

Add values like these to the Laravel application's `.env`:

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
```

For a topic, set:

```dotenv
TELEGRAM_INBOX_MESSAGE_THREAD_ID=42
```

For a direct messages topic, set:

```dotenv
TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID=77
```

Do not commit `.env`.

## 15. Configure config/telegram-bot.php

The published config already contains a default bot:

```php
'default' => env('TELEGRAM_BOT', 'default'),

'bots' => [
    'default' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
        'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
    ],
],
```

Add a channel mapping:

```php
'channels' => [
    'inbox' => [
        'bot' => 'default',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_INBOX_MESSAGE_THREAD_ID'),
        'direct_messages_topic_id' => env('TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID'),
    ],
],

'logging' => [
    'enabled' => env('TELEGRAM_BOT_LOGGING_ENABLED', true),
],

'webhook' => [
    'bot' => env('TELEGRAM_WEBHOOK_BOT', env('TELEGRAM_BOT', 'default')),
    'secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN'),
    'require_secret' => env('TELEGRAM_WEBHOOK_REQUIRE_SECRET', env('APP_ENV') === 'production'),
    'handler' => null,
    'dispatch_event' => true,
    'route' => [
        'enabled' => env('TELEGRAM_WEBHOOK_ROUTE_ENABLED', true),
        'uri' => env('TELEGRAM_WEBHOOK_ROUTE_URI', 'telegram-bot/webhook'),
        'name' => env('TELEGRAM_WEBHOOK_ROUTE_NAME', 'telegram-bot.webhook'),
        'middleware' => [],
    ],
],
```

Each configured channel must have a non-empty `chat_id`. The package validates channel mappings when `channel('name')` is resolved so a missing Laravel environment value fails with a configuration exception instead of a delayed Telegram API error.

For multiple bots:

```php
'bots' => [
    'support' => [
        'token' => env('TELEGRAM_SUPPORT_BOT_TOKEN'),
        'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
        'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
    ],
    'ops' => [
        'token' => env('TELEGRAM_OPS_BOT_TOKEN'),
        'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
        'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
    ],
],

'channels' => [
    'inbox' => [
        'bot' => 'support',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_INBOX_MESSAGE_THREAD_ID'),
        'direct_messages_topic_id' => env('TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID'),
    ],
    'deployments' => [
        'bot' => 'ops',
        'chat_id' => env('TELEGRAM_DEPLOYMENTS_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_DEPLOYMENTS_MESSAGE_THREAD_ID'),
    ],
],
```

## 16. Send A Test Message From Laravel

First verify that Laravel loads the expected bot token:

```bash
php artisan telegram-bot:me --bot=default
```

Then send an end-to-end delivery test through a configured channel:

```bash
php artisan telegram-bot:send-test --channel=inbox
```

For an explicit chat or topic:

```bash
php artisan telegram-bot:send-test \
  --bot=default \
  --chat-id=-1001234567890 \
  --message-thread-id=42
```

Use Laravel constructor injection in controllers, jobs, listeners, commands, or services:

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final class SendTelegramSetupMessage
{
    public function __construct(
        private TelegramBot $telegram,
    ) {
    }

    public function __invoke(): void
    {
        $this->telegram->channel('inbox')->sendMessage([
            'text' => 'Telegram setup test',
        ]);
    }
}
```

You may also type-hint `AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager`.
Use the concrete `AlexItDev91\LaravelTelegramBot\TelegramBot` type when you want IDE autocomplete for every native Telegram helper method.

The facade remains available:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::channel('inbox')->sendMessage([
    'text' => 'Telegram setup test',
]);
```

Or select a bot directly:

```php
TelegramBot::bot('support')->sendMessage([
    'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
    'text' => 'Telegram setup test',
]);
```

Use `channel('inbox')` when the destination is configured in `config/telegram-bot.php`. Use `bot('support')` when the code should provide `chat_id` directly.

For uploads, use `InputFile::fromPath()`. The package converts top-level files and nested media files to multipart form data:

```php
use AlexItDev91\LaravelTelegramBot\InputFile;

$this->telegram->bot('support')->sendMediaGroup([
    'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
    'media' => [
        [
            'type' => 'photo',
            'media' => InputFile::fromPath(storage_path('app/photo.jpg')),
        ],
    ],
]);
```

If the host app needs custom transport, bind `GuzzleHttp\ClientInterface` before resolving the bot client. Keep `http_errors` disabled so Telegram API error payloads remain available to the SDK:

```php
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

$this->app->bind(ClientInterface::class, fn (): ClientInterface => new Client([
    'timeout' => 5,
    'http_errors' => false,
]));
```

## 17. Receive Telegram Webhooks

The package registers `POST /telegram-bot/webhook` by default. Configure a secret token and pass the same value to Telegram:

```dotenv
TELEGRAM_WEBHOOK_SECRET_TOKEN=change-this-secret
TELEGRAM_WEBHOOK_REQUIRE_SECRET=true
```

The webhook secret must follow Telegram's allowed format: 1-256 characters using only letters, numbers, `_`, and `-`.

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => ['message', 'callback_query'],
]);
```

Incoming updates can be handled with `AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler` or by listening for `AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived`.

See [docs/WEBHOOKS.md](WEBHOOKS.md) for route configuration, handler examples, event usage, secret validation, and production safety notes.

## 18. Test With Tinker

Run:

```bash
php artisan tinker
```

Then:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::channel('inbox')->sendMessage([
    'text' => 'Telegram setup test from Tinker',
]);
```

If the message appears in Telegram, the bot, channel, identifiers, package config, and Laravel integration are connected correctly.

## 19. Troubleshooting

If `getUpdates` is empty:

1. Send a fresh message after adding the bot.
2. Remove and re-add the bot as an administrator.
3. Use `getChat` with a public or temporary public channel username.
4. Check whether a webhook is consuming updates. If needed, inspect `getWebhookInfo`.

If Laravel cannot send a message:

1. Check `TELEGRAM_BOT_TOKEN`.
2. Check that config cache was refreshed after changing `.env`:

```bash
php artisan config:clear
```

3. Check that `TELEGRAM_INBOX_CHAT_ID` includes the full negative value.
4. Check that the bot is still a channel administrator or group member.
5. Check that the bot has permission to post messages.
6. Check `TELEGRAM_INBOX_MESSAGE_THREAD_ID` when using topics.
7. Check Laravel logs for Telegram API errors.

If Telegram returns `chat not found`, the bot usually cannot access that chat or the chat ID is wrong.

If Telegram returns `Forbidden`, the bot may have been removed, blocked, or denied permission to post.

If Telegram webhook delivery fails:

1. Check `getWebhookInfo` for the last delivery error.
2. Check that `TELEGRAM_WEBHOOK_SECRET_TOKEN` matches the `secret_token` passed to `setWebhook`.
3. Check that the route URL is public HTTPS and points to `route('telegram-bot.webhook')` or your configured route.
4. Check that the handler returns quickly or dispatches slow work to a queue.
5. Check Laravel warning/error logs for rejected secrets, invalid payloads, invalid handler configuration, and handler failures.

## 20. Production Safety Checklist

- Store bot tokens only in `.env`, secret storage, or deployment platform secrets.
- Do not commit real bot tokens, private chat IDs, webhook secrets, logs, or screenshots.
- Set a webhook secret token and pass it to Telegram with `setWebhook`.
- Keep webhook secret enforcement enabled in production with `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true`.
- Keep safe operational logging enabled with `TELEGRAM_BOT_LOGGING_ENABLED=true`.
- Give the bot only the permissions it needs.
- Use a private channel or private group for operational notifications.
- Rotate the token if it appears anywhere public.
- Send a harmless test message before sending real customer, inbox, or operational content.
- Keep a record of bot username, bot ID, destination chat ID, and topic ID in a private operations document.
