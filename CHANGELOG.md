# Changelog

All notable changes to this package are documented here.

This package follows semantic versioning for release tags:

- Patch version for small, compatible updates, bug fixes, documentation updates, tests, and internal cleanup.
- Minor version for significant compatible changes, new features, new public SDK behavior, or Telegram Bot API surface expansions.
- Major version for breaking changes.

## [1.5.1] - 2026-06-01

- Added safe warning/error logging for webhook security rejections, invalid webhook payloads, invalid handler configuration, and handler failures.
- Added safe Telegram Bot API failure and transport response logging for Laravel-resolved bot clients and manually supplied PSR loggers.
- Added `TELEGRAM_BOT_LOGGING_ENABLED` to control package logging without logging tokens, secret headers, request payloads, response bodies, chat IDs, or message text.

## [1.5.0] - 2026-06-01

- Added production fail-closed webhook secret enforcement with `TELEGRAM_WEBHOOK_REQUIRE_SECRET`.
- Switched file upload multipart parts to lazy PSR-7 streams so files are not opened during payload construction.
- Expanded the Telegram Bot API surface check to verify documented method parameter names, types, and required flags against the official Telegram documentation.
- Added PHPStan static analysis and a Composer `analyse` command.
- Declared `illuminate/routing` as a runtime dependency for the Laravel webhook route integration.

## [1.4.0] - 2026-06-01

- Added typed request DTOs and value objects for Telegram Payments, Stars, subscriptions, and paid media.
- Added typed Telegram Passport helpers for authorization scopes, passport element errors, `setPassportDataErrors`, and Passport payload decryption.
- Added typed Games helpers for `sendGame`, `setGameScore`, `getGameHighScores`, `CallbackGame`, and `InlineQueryResultGame`.
- Added payment, Passport, and game convenience accessors to `TelegramWebhookUpdate`.
- Added dedicated Payments, Telegram Passport, paid media, and Games documentation with examples.

## [1.3.2] - 2026-06-01

- Forced Telegram client requests to disable Guzzle HTTP exceptions per request so Telegram `ok: false` responses keep their API error metadata.
- Added validation that failed Telegram API responses include the required `description` field.
- Added a local Telegram Bot API surface check script and Composer command for release-time verification against the official Telegram documentation and changelog.

## [1.3.1] - 2026-06-01

- Added a package cover image to the README documentation.

## [1.3.0] - 2026-06-01

- Added a configurable Laravel webhook receiver route for incoming Telegram updates.
- Added webhook secret-token validation for `X-Telegram-Bot-Api-Secret-Token`.
- Added `TelegramWebhookUpdate`, `TelegramWebhookHandler`, and `TelegramWebhookReceived` event support.
- Added webhook handler response normalization for JSON arrays, strings, and Symfony/Laravel responses.
- Added detailed webhook setup, security, handler, event, and route documentation.

## [1.2.0] - 2026-06-01

- Added recursive multipart serialization for nested `InputFile` values using Telegram `attach://` references.
- Added validation for malformed successful Telegram API responses that omit the required `result` field.
- Added support for Laravel container-bound `GuzzleHttp\ClientInterface` transport customization.
- Improved bot config fallback so empty per-bot values inherit shared config values.
- Expanded docs for file uploads, Laravel transport customization, and concrete-vs-contract DI usage.

## [1.1.0] - 2026-06-01

- Added `AlexItDev91\LaravelTelegramBot\TelegramBot` as the primary injectable Laravel service for constructor DI.
- Kept facade, concrete manager, manager contract, default client contract, and default concrete client bindings compatible with the same container-backed behavior.
- Documented both constructor injection and facade usage.
- Added strict agent instructions for automatic release commit, tag, and push completion.

## [1.0.1] - 2026-06-01

- Improved README documentation navigation by listing available package documentation with short descriptions.

## [1.0.0] - 2026-06-01

- Initial stable package release.
- Supports Telegram Bot API 10.0.
- Provides Laravel 12/13 service provider, facade, config publishing, multi-bot config, channel mappings, DTOs, enums, InputFile support, complete method surface, API documentation, setup guide, and Laravel Boost resources.
