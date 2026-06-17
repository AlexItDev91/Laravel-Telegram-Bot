# Console Commands

This package ships interactive Artisan commands for Laravel applications. They use Laravel Prompts for modern console input and keep tokens, webhook secrets, chat IDs, and message text out of logs.

## Commands

| Command | Purpose |
| --- | --- |
| `telegram-bot:install` | Publish package config and print copy-ready Laravel env/config snippets. |
| `telegram-bot:doctor` | Diagnose bot config, webhook secret policy, route registration, and Telegram API reachability. |
| `telegram-bot:make-handler` | Scaffold webhook command, update-type, or fallback handler classes. |
| `telegram-bot:me` | Verify a configured bot token and print Telegram bot identity fields. |
| `telegram-bot:send-test` | Send a delivery test message to a configured channel, chat, forum topic, or direct messages topic. |
| `telegram-bot:webhook:set` | Register a Telegram webhook with optional secret token and allowed update types. |
| `telegram-bot:webhook:delete` | Delete the Telegram webhook, optionally dropping pending updates. |
| `telegram-bot:webhook:info` | Show Telegram webhook delivery status. |
| `telegram-bot:updates` | Fetch updates and print parsed `chat_id`, `message_thread_id`, and `direct_messages_topic_id` values. |

All commands accept `--bot=<name>` when the host application has multiple configured bots.

## Install

Run:

```bash
php artisan telegram-bot:install
```

The command publishes `config/telegram-bot.php`, asks for bot/channel names when needed, optionally validates a bot token through `getMe`, and prints copy-ready environment values:

```text
TELEGRAM_BOT=default
TELEGRAM_BOT_TOKEN=<bot-token-from-botfather>
TELEGRAM_BOT_API_URL=https://api.telegram.org
TELEGRAM_BOT_TIMEOUT=10
TELEGRAM_WEBHOOK_SECRET_TOKEN=<letters-numbers-underscore-or-dash>
TELEGRAM_WEBHOOK_REQUIRE_SECRET=true
TELEGRAM_INBOX_CHAT_ID=<chat-id>
TELEGRAM_INBOX_MESSAGE_THREAD_ID=<message-thread-id-if-topic>
TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID=<direct-messages-topic-id-if-needed>
```

Non-interactive example:

```bash
php artisan telegram-bot:install --bot=support --channel=inbox --skip-token-check
```

The command does not write secrets into `.env`. Store real bot tokens and webhook secrets in the host application's environment or secret manager.

## Make Handler

Scaffold webhook handlers directly into the host Laravel app:

```bash
php artisan telegram-bot:make-handler StartCommand --command=start
php artisan telegram-bot:make-handler Telegram/Handlers/CallbackQueryHandler --update=callback_query
php artisan telegram-bot:make-handler FallbackHandler --fallback
```

The command creates classes under `app/Telegram/Commands` or `app/Telegram/Handlers`, adds the matching webhook contract, and prints the `config/telegram-bot.php` registration snippet. Use `--force` only when you intentionally want to overwrite an existing handler file.

## Bot Identity

Use `telegram-bot:me` when you want a standalone token/config check without running the installer:

```bash
php artisan telegram-bot:me --bot=default
```

The command calls Telegram `getMe` and prints fields such as `id`, `username`, `can_join_groups`, `supports_inline_queries`, `can_connect_to_business`, and `supports_guest_queries`.

For raw Telegram JSON:

```bash
php artisan telegram-bot:me --bot=default --raw
```

## Doctor

Use `telegram-bot:doctor` when checking a host application before or after deploy:

```bash
php artisan telegram-bot:doctor --bot=default
```

The command checks:

- configured bot resolution;
- webhook secret policy, including fail-closed production settings;
- package webhook route registration;
- live `getMe` and `getWebhookInfo` calls.

For CI jobs or offline config checks, skip Telegram network calls:

```bash
php artisan telegram-bot:doctor --bot=default --skip-telegram
```

## Set Webhook

Interactive:

```bash
php artisan telegram-bot:webhook:set
```

Non-interactive:

```bash
php artisan telegram-bot:webhook:set \
  --bot=default \
  --url=https://example.com/telegram-bot/webhook \
  --secret="${TELEGRAM_WEBHOOK_SECRET_TOKEN}" \
  --allowed-updates=message \
  --allowed-updates=callback_query
```

Useful options:

| Option | Description |
| --- | --- |
| `--url=` | Public HTTPS webhook URL. |
| `--secret=` | Telegram webhook secret token. The command never prints the value. |
| `--no-secret` | Register the webhook without a secret token. Not recommended for production. |
| `--allowed-updates=` | Repeatable update type filter. Omit to let Telegram send all default updates. |
| `--max-connections=` | Telegram webhook max connections. |
| `--ip-address=` | Fixed IP address for Telegram webhook delivery. |
| `--drop-pending-updates` | Drop pending updates while setting the webhook. |

The secret token must match Telegram's contract: 1-256 characters using only `A-Z`, `a-z`, `0-9`, `_`, and `-`.

## Webhook Info

```bash
php artisan telegram-bot:webhook:info --bot=default
```

The command displays a table with `url`, `pending_update_count`, last error fields, max connections, and allowed updates.

For raw Telegram JSON:

```bash
php artisan telegram-bot:webhook:info --bot=default --raw
```

## Delete Webhook

Interactive:

```bash
php artisan telegram-bot:webhook:delete --bot=default
```

Non-interactive:

```bash
php artisan telegram-bot:webhook:delete --bot=default --yes
```

Drop pending updates at the same time:

```bash
php artisan telegram-bot:webhook:delete --bot=default --drop-pending-updates --yes
```

## Discover Chat IDs And Thread IDs

Telegram `getUpdates` and webhooks are mutually exclusive. If a webhook is active, the command warns you. In interactive mode it can delete the webhook before polling; in automation use `--delete-webhook` or skip the check with `--skip-webhook-check`.

1. Add the bot to the target chat, channel, group, or topic.
2. Send a fresh test message in the exact destination.
3. Run:

```bash
php artisan telegram-bot:updates --bot=default
```

The command prints copy-ready values first:

```text
TELEGRAM_CHAT_ID=-1009007199254740991
TELEGRAM_MESSAGE_THREAD_ID=42
```

It also prints parsed key-value lines instead of raw JSON:

```text
Parsed Telegram chat references for bot [default]
Update 1001 (message)
  source: message
  chat_id: -1009007199254740991
  message_thread_id: 42
  message_id: 10
  chat_type: supergroup
  chat_title: Operations
```

Use `chat_id` for normal channel mappings. Use `message_thread_id` only when the destination is a forum topic. For direct messages topics, use `direct_messages_topic_id`.

Non-interactive examples:

```bash
php artisan telegram-bot:updates \
  --bot=default \
  --limit=20 \
  --timeout=0 \
  --allowed-updates=message \
  --allowed-updates=channel_post
```

If a webhook is active and you want to poll updates:

```bash
php artisan telegram-bot:updates --bot=default --delete-webhook
```

Raw JSON remains available for debugging:

```bash
php artisan telegram-bot:updates --bot=default --raw
```

## Send A Test Message

Use `telegram-bot:send-test` after configuring a channel or after discovering IDs. This verifies the full Laravel path from configured bot token to Telegram delivery.

Interactive:

```bash
php artisan telegram-bot:send-test
```

Send to a configured channel:

```bash
php artisan telegram-bot:send-test --channel=inbox
```

Send to a configured forum topic channel:

```php
'channels' => [
    'deployments' => [
        'bot' => 'default',
        'chat_id' => env('TELEGRAM_DEPLOYMENTS_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_DEPLOYMENTS_MESSAGE_THREAD_ID'),
    ],
],
```

```bash
php artisan telegram-bot:send-test --channel=deployments --text="Deployment topic test"
```

Send to an explicit chat or channel ID:

```bash
php artisan telegram-bot:send-test \
  --bot=default \
  --chat-id=-1009007199254740991 \
  --text="Telegram delivery test"
```

Send to an explicit forum topic:

```bash
php artisan telegram-bot:send-test \
  --bot=default \
  --chat-id=-1009007199254740991 \
  --message-thread-id=42 \
  --text="Forum topic delivery test"
```

Send to an explicit direct messages topic:

```bash
php artisan telegram-bot:send-test \
  --bot=default \
  --chat-id=123456789 \
  --direct-messages-topic-id=77 \
  --text="Direct messages topic delivery test"
```

Useful options:

| Option | Description |
| --- | --- |
| `--channel=` | Configured Laravel channel name from `config/telegram-bot.php`. |
| `--chat-id=` | Explicit Telegram chat ID or target channel username. |
| `--message-thread-id=` | Forum topic ID for explicit chat targets. |
| `--direct-messages-topic-id=` | Direct messages topic ID for explicit chat targets. |
| `--text=` | Test message text. Defaults to `Telegram Bot test message from Laravel.` |
| `--parse-mode=` | Optional parse mode: `MarkdownV2`, `HTML`, or `Markdown`. |
| `--disable-notification` | Send the test message silently. |
| `--protect-content` | Protect the message content from forwarding and saving. |

## Multi-Bot Usage

For a named bot:

```bash
php artisan telegram-bot:me --bot=support
php artisan telegram-bot:webhook:info --bot=support
php artisan telegram-bot:updates --bot=support
php artisan telegram-bot:send-test --bot=support --chat-id=-1001234567890
```

Configure named bots in `config/telegram-bot.php`:

```php
'bots' => [
    'support' => [
        'token' => env('TELEGRAM_SUPPORT_BOT_TOKEN'),
        'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
        'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
    ],
],
```

## Safety Notes

- Do not paste real tokens or secrets into committed files.
- Use `telegram-bot:me` before webhook setup when you need to verify which bot token is loaded.
- Use `telegram-bot:send-test` after `telegram-bot:updates` to confirm the bot can write to the selected chat or topic.
- Use `telegram-bot:webhook:info` after deployment to verify Telegram sees the expected URL.
- Use `telegram-bot:updates` only for discovery or polling workflows; if a webhook is active, Telegram will not also deliver the same updates through `getUpdates`.
- Keep `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true` in production.
