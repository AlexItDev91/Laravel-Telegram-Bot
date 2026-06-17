# Troubleshooting

Use the package commands first.
They keep secrets out of output while still verifying the Telegram and Laravel integration path.

## Fast Checks

| Problem | Command or check |
| --- | --- |
| Unsure whether the token is valid | `php artisan telegram-bot:me --bot=default` |
| Need a chat ID or topic ID | `php artisan telegram-bot:updates --bot=default` |
| Need to verify delivery | `php artisan telegram-bot:send-test --channel=inbox` |
| Webhook may be wrong | `php artisan telegram-bot:webhook:info --bot=default` |
| Need to switch back to polling | `php artisan telegram-bot:webhook:delete --bot=default --yes` |
| Need raw Telegram output | Add `--raw` to identity, webhook info, or update commands where supported. |

## Common Symptoms

| Symptom | Likely cause | Fix |
| --- | --- | --- |
| `Telegram Bot token is not configured.` | Missing `TELEGRAM_BOT_TOKEN` or wrong bot name. | Check env values and `config('telegram-bot.bots')`. |
| `Telegram Bot channel [name] is not configured.` | Code calls an unknown channel. | Add the channel mapping or use `bot()->sendMessage(...)`. |
| `Bad Request: chat not found` | Wrong `chat_id`, bot not added, or bot lacks access. | Re-add the bot and rediscover IDs with `telegram-bot:updates`. |
| Updates are empty | Webhook is active or no fresh message exists. | Delete webhook for polling or send a new message in the exact destination. |
| Forum topic delivery goes to the main chat | Missing or wrong `message_thread_id`. | Discover the topic ID from an update sent inside the topic. |
| Webhook returns 403 | Missing or wrong `X-Telegram-Bot-Api-Secret-Token`. | Register webhook with the configured secret and check `TELEGRAM_WEBHOOK_SECRET_TOKEN`. |
| Production webhook rejects all requests | `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true` but no secret is configured. | Configure a valid secret token or intentionally disable the requirement outside production. |
| File upload fails | Path does not exist, app cannot read it, or an HTTP URL/file ID should be used instead. | Check the path and pass `InputFile::fromPath(...)` only for readable local files. |

## Webhook And Polling Conflict

Telegram `getUpdates` and webhooks are mutually exclusive.
If a webhook is active, polling commands cannot receive new updates until the webhook is removed.

```bash
php artisan telegram-bot:webhook:info --bot=default
php artisan telegram-bot:webhook:delete --bot=default --yes
php artisan telegram-bot:updates --bot=default
```

After ID discovery, register the webhook again if the application uses incoming updates:

```bash
php artisan telegram-bot:webhook:set \
  --bot=default \
  --url=https://example.com/telegram-bot/webhook \
  --secret="${TELEGRAM_WEBHOOK_SECRET_TOKEN}"
```

## Safe Logs

When logging is enabled, package logs can help identify:

- rejected webhook secret tokens;
- invalid webhook payloads;
- invalid handler configuration;
- handler failures;
- Telegram API failures;
- transport-level failures.

Logs intentionally omit bot tokens, secret headers, request payloads, response bodies, chat IDs, and message text.

## Debug Checklist

1. The host Laravel app has the expected env values loaded.
2. `php artisan config:clear` has been run after env changes in local debugging.
3. `telegram-bot:me` returns the expected bot username and ID.
4. The bot is a member or admin of the target chat/channel.
5. The destination ID includes the minus sign for groups and channels.
6. Topic messages include the correct `message_thread_id`.
7. Production webhooks use a valid secret token.
8. Slow webhook work is dispatched to jobs instead of blocking Telegram's request.
