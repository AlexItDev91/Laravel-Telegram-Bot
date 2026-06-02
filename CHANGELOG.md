# Changelog

All notable changes to this package are documented here.

This package follows semantic versioning for release tags:

- Patch version for small, compatible updates, bug fixes, documentation updates, tests, and internal cleanup.
- Minor version for significant compatible changes, new features, new public SDK behavior, or Telegram Bot API surface expansions.
- Major version for breaking changes.

## [1.8.0] - 2026-06-02

- Added typed webhook update DTO accessors for common Telegram `Message`, `Chat`, and `User` objects, including effective message, chat, and user helpers for Laravel handlers.
- Added a Packagist latest-version badge to the README.

## [1.7.4] - 2026-06-02

- Redacted bot tokens from propagated Telegram transport exception messages while preserving the original exception as the previous throwable.

## [1.7.3] - 2026-06-02

- Simplified the README documentation section from a table into a compact unordered table of contents.

## [1.7.2] - 2026-06-02

- Reworked the README documentation section to link to the published GitHub Pages documentation with readable page names.
- Updated README webhook and console command cross-links to use the published documentation pages.
- Added the package cover image from the README to the Writerside overview page.
- Shortened Writerside page titles so the navigation does not repeat the package name on every page.

## [1.7.1] - 2026-06-02

- Added a Writerside documentation module with structured package documentation, examples, tables, troubleshooting, maintenance notes, and imported full method/API reference topics.
- Added a GitHub Actions workflow that builds Writerside docs, checks the build report, and deploys the generated site to GitHub Pages.
- Documented the Writerside source and GitHub Pages workflow in the README.

## [1.7.0] - 2026-06-02

- Added `telegram-bot:me` for standalone configured bot identity and token checks through Telegram `getMe`.
- Added `telegram-bot:send-test` for end-to-end Laravel delivery checks to configured channels, explicit chats, forum topics, and direct messages topics.
- Documented the full console workflow from install and identity checks through ID discovery, delivery testing, and webhook management.
- Added direct messages topic snippets to the installer output, package config comments, setup guide, and Laravel Boost resources.

## [1.6.0] - 2026-06-02

- Added interactive Laravel Prompts Artisan commands for package installation, webhook registration, webhook deletion, webhook status inspection, and Telegram update discovery.
- Added parsed `getUpdates` discovery output for copy-ready `chat_id`, `message_thread_id`, and `direct_messages_topic_id` values.
- Added dedicated console command documentation with interactive and non-interactive examples.
- Declared runtime dependencies on `illuminate/console` and `laravel/prompts` for the new command surface.

## [1.5.4] - 2026-06-02

- Added early validation for typed Payments, Stars, paid media, Telegram Passport, and Games DTO payloads.
- Added Laravel configuration validation for bot API URLs, timeouts, channel chat IDs, webhook update IDs, and webhook secret token format.
- Prevented typed DTO `extra` payloads from overriding constructor-backed fields.
- Documented the package validation model and the intentional raw `call()` escape hatch for newly released Telegram Bot API methods.

## [1.5.3] - 2026-06-01

- Shortened the method reference index source links and made method names link to their local reference sections.
- Moved release automation instructions out of public package documentation and into `AGENTS.md`.

## [1.5.2] - 2026-06-01

- Fixed the method reference Markdown table so PHP union type pipes render inside the SDK call column instead of splitting table cells.

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
