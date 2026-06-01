# Telegram Bot Setup Guide

This guide explains how to create a Telegram bot, create a destination chat, add the bot there, and find the identifiers needed by a Laravel application.

Primary Telegram references:

- [Telegram Bots](https://core.telegram.org/bots)
- [Telegram Bot Features](https://core.telegram.org/bots/features)
- [Telegram Bot API](https://core.telegram.org/bots/api)
- [BotFather](https://t.me/BotFather)

## 1. Create A Bot

1. Open Telegram.
2. Search for `@BotFather`.
3. Open the verified BotFather chat.
4. Press `Start`.
5. Send `/newbot`.
6. Enter a display name for the bot, for example `Company Inbox`.
7. Enter a username for the bot. It must end with `bot`, for example `CompanyInboxBot`.
8. BotFather will send a bot token.
9. Copy the token and store it in a safe password manager.

Do not paste the token into public chats, screenshots, GitHub issues, commits, logs, or documentation.

## 2. Optional Bot Settings

In the BotFather chat:

1. Send `/mybots`.
2. Select the bot.
3. Open `Edit Bot`.
4. Set the bot picture, description, about text, and commands if needed.

Useful commands for a simple inbox notification bot:

```text
status - Show bot status
help - Show available commands
```

## 3. Create A Destination Channel

Use a channel when the bot only needs to post notifications and people do not need to chat there.

1. Open Telegram.
2. Press the new message button.
3. Select `New Channel`.
4. Enter a channel name, for example `Company Inbox Alerts`.
5. Add an optional description.
6. Choose `Private Channel` unless the channel must be public.
7. Finish channel creation.

## 4. Add The Bot To The Channel

1. Open the channel.
2. Open the channel profile.
3. Open `Administrators`.
4. Press `Add Admin`.
5. Search for the bot username, for example `@CompanyInboxBot`.
6. Add the bot.
7. Give it the minimum permission needed to post messages.

For notification-only usage, the bot usually needs permission to post messages. Avoid giving broad admin rights unless the bot needs them.

## 5. Create A Group Instead Of A Channel

Use a group when people need to reply, discuss, or work around the notification.

1. Open Telegram.
2. Press the new message button.
3. Select `New Group`.
4. Add at least one person or the bot.
5. Enter a group name, for example `Company Inbox`.
6. Open the group profile.
7. Add the bot if it was not added during creation.
8. Open bot permissions and allow only what is needed.

If the group has forum topics enabled, each topic has its own `message_thread_id`.

## 6. Send A Test Message

Before looking for identifiers:

1. Send a normal message into the channel or group.
2. If using a group topic, send the message inside the exact topic.
3. If the bot has privacy restrictions in groups and must read messages, adjust BotFather privacy settings. For notification-only posting, this is usually not needed.

## 7. Find The Bot Token

If the token was lost:

1. Open `@BotFather`.
2. Send `/mybots`.
3. Select the bot.
4. Open `API Token`.
5. Copy the token.

If the token may be exposed, use BotFather to revoke it and generate a new one.

## 8. Find The Chat ID With getUpdates

This is the simplest manual method for private groups and many channel setups.

1. Make sure the bot is added to the group or channel.
2. Send a fresh test message in the group or channel.
3. Open this URL in a browser, replacing `<BOT_TOKEN>` with the real token:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getUpdates
```

4. Look for `chat`.
5. Copy the `id` value.

Example shape:

```json
{
  "message": {
    "chat": {
      "id": -1001234567890,
      "title": "Company Inbox Alerts",
      "type": "channel"
    }
  }
}
```

Use the full value, including the minus sign.

## 9. Find A Channel ID When getUpdates Is Empty

Sometimes `getUpdates` is empty because the bot did not receive a recent update.

Try this:

1. Remove the bot from the channel.
2. Add the bot again as an administrator.
3. Send a new post in the channel.
4. Open `https://api.telegram.org/bot<BOT_TOKEN>/getUpdates` again.

If it is still empty, use a temporary public channel username:

1. Open channel settings.
2. Set a temporary public username, for example `company_inbox_alerts_temp`.
3. Open this URL:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getChat?chat_id=@company_inbox_alerts_temp
```

4. Copy the `result.id` value.
5. Remove the temporary public username if the channel should stay private.

## 10. Find A Topic ID

For forum groups, Telegram uses `message_thread_id` to identify topics.

1. Enable topics in the group if needed.
2. Open the exact topic.
3. Send a test message in that topic.
4. Open:

```text
https://api.telegram.org/bot<BOT_TOKEN>/getUpdates
```

5. Find `message_thread_id` in the message object.
6. Use that value as the channel `message_thread_id`.

If `message_thread_id` is absent, the message was not sent inside a forum topic.

## 11. Configure Laravel Environment Values

In your Laravel application's `.env`, add values like:

```dotenv
TELEGRAM_BOT=default
TELEGRAM_BOT_TOKEN=123456:replace-with-real-token
TELEGRAM_BOT_API_URL=https://api.telegram.org
TELEGRAM_BOT_TIMEOUT=10

TELEGRAM_INBOX_CHAT_ID=-1001234567890
TELEGRAM_INBOX_MESSAGE_THREAD_ID=
```

Do not commit `.env`.

## 12. Configure A Package Channel

In `config/telegram-bot.php`, configure channels like this:

```php
'channels' => [
    'inbox' => [
        'bot' => 'default',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_INBOX_MESSAGE_THREAD_ID'),
    ],
],
```

Then application code can send through the configured channel:

```php
TelegramBot::channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);
```

## 13. Test Sending A Message

Use a safe test message first:

```php
TelegramBot::channel('inbox')->sendMessage([
    'text' => 'Telegram setup test',
]);
```

If the message does not appear:

1. Check the bot token.
2. Check that the bot is still in the channel or group.
3. Check that the bot can post messages.
4. Check that `chat_id` includes the minus sign.
5. If using a topic, check `message_thread_id`.
6. Check Laravel logs for Telegram API errors.

## 14. Production Safety Checklist

- Store the token only in secret storage or `.env`.
- Do not commit tokens or chat IDs if the chat is private.
- Give the bot only the permissions it needs.
- Use a private channel or private group for operational notifications.
- Rotate the token if it appears in a screenshot, commit, log, or public message.
- Test with a harmless message before sending real customer or inbox content.
