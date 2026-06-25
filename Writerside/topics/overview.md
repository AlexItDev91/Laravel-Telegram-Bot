# Laravel Telegram Bot

![Laravel Telegram Bot package cover](package-cover.png){ width="700" }

Laravel Telegram Bot is a Laravel-friendly PHP SDK for the official Telegram Bot API.
It is built for Laravel 13 applications.
It keeps the core client usable outside Laravel without hard Illuminate runtime requirements, and adds Laravel package discovery, configuration, facade access, webhook receiving, console helpers, and typed request DTOs for the workflows where stronger payload validation reduces production risk.

The package targets Telegram Bot API 10.1, released on 2026-06-11.
The raw `call(method, parameters)` API is always available, so newly released Telegram methods can be used before a typed helper is added.

## Compatibility

| Area | Supported versions or behavior |
| --- | --- |
| PHP | `^8.4` |
| Laravel integration | `^13.0` in the host app |
| HTTP client | Guzzle `^7.8`, customizable through Laravel container binding or direct client construction |
| Telegram API target | Telegram Bot API 10.1 |
| Request formats | JSON by default, multipart when `InputFile` values are present |
| Main package namespace | `AlexItDev91\LaravelTelegramBot` |
| Laravel namespaces | `AlexItDev91\LaravelTelegramBot\Laravel` and `AlexItDev91\LaravelTelegramBot\Facades` |

Version `v1.19.1` is the final 1.x release for older host applications. Starting with `v2.0.0`, the package supports only PHP 8.4 and Laravel 13.

## What The Package Provides

| Capability | Where to read next |
| --- | --- |
| Composer installation and package discovery | [Installation](installation.md) |
| Single-bot and multi-bot config | [Configuration](configuration.md) |
| Constructor injection, facade calls, channel mappings, and raw calls | [Usage](usage.md) |
| BotFather, chat ID, topic ID, and safe delivery setup | [End-to-end setup guide](telegram-setup.md) |
| Artisan install, identity, webhook, update discovery, and delivery-test commands | [Console commands](console-commands.md) |
| Incoming Telegram webhook route, secret validation, handlers, and events | [Webhooks](webhooks.md) |
| File uploads and custom HTTP clients | [Files and HTTP](files-and-http.md) |
| Business, managed bots, Stars, paid media, Passport, and Games DTOs | [Payments, Passport, and Games](payments-passport-games.md) |
| Supported method matrix | [API method support](api-surface.md) |
| Full native method reference | [API method reference](method-reference.md) |

## Recommended Integration Path

| Step | Action | Result |
| --- | --- | --- |
| 1 | Install the package with Composer. | Laravel discovers the service provider and facade. |
| 2 | Publish config or run `telegram-bot:install`. | `config/telegram-bot.php` is available in the host app. |
| 3 | Store bot tokens and secrets in environment variables. | Credentials stay out of committed files. |
| 4 | Add a channel mapping for common destinations. | Application code can call `channel('name')` without repeating `chat_id`. |
| 5 | Verify the bot with `telegram-bot:me`. | Token and Telegram identity are checked before delivery. |
| 6 | Send a test message with `telegram-bot:send-test`. | The full Laravel -> Telegram path is verified. |
| 7 | Configure webhooks only when the application is ready for incoming updates. | `POST /telegram-bot/webhook` can receive and validate updates. |

## Which Calling Style To Use

| Use case | Recommended surface |
| --- | --- |
| Normal Laravel service, job, listener, or controller | Constructor injection of `TelegramBot` |
| Static-style convenience in small code paths | `AlexItDev91\LaravelTelegramBot\Facades\TelegramBot` |
| Reusable destination such as alerts, deployments, or inbox | `channel('name')` from config |
| Multiple bots with different tokens | `bot('support')`, `bot('shop')`, or channel-specific `bot` values |
| Newly released Telegram method | `call('methodName', [...])` |
| Business, managed bot, Stars, Passport, paid media, or Games payloads | Typed DTOs under `AlexItDev91\LaravelTelegramBot\DTO` |

## Source Of Truth

The package documentation is aligned with these primary sources:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Telegram Bot API changelog](https://core.telegram.org/bots/api-changelog)
- [Telegram Passport manual](https://core.telegram.org/passport)

When Telegram publishes new Bot API changes, maintainers should update the SDK surface, tests, public docs, Writerside topics, and release notes together.
