# Changelog

All notable changes to this package are documented here.

This package follows semantic versioning for release tags:

- Patch version for small, compatible updates, bug fixes, documentation updates, tests, and internal cleanup.
- Minor version for significant compatible changes, new features, new public SDK behavior, or Telegram Bot API surface expansions.
- Major version for breaking changes.

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
