# Changelog

All notable changes to this package are documented here.

This package follows semantic versioning for release tags:

- Patch version for small, compatible updates, bug fixes, documentation updates, tests, and internal cleanup.
- Minor version for significant compatible changes, new features, new public SDK behavior, or Telegram Bot API surface expansions.
- Major version for breaking changes.

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
