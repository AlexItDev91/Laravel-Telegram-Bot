# Changelog

All notable changes to this package are documented here.

This package follows semantic versioning for release tags:

- Patch version for small, compatible updates, bug fixes, documentation updates, tests, and internal cleanup.
- Minor version for significant compatible changes, new features, new public SDK behavior, or Telegram Bot API surface expansions.
- Major version for breaking changes.

## [2.11.0] - 2026-06-10

- Added `telegram-bot:make-handler` for scaffolding host application webhook command, update-type, and fallback handler classes.
- Generated handlers include the matching webhook contract, dispatcher discovery attribute where applicable, and copy-ready registration hints for `config/telegram-bot.php`.
- Updated console command registration, feature tests, README, Markdown docs, Writerside docs, and Boost resources for handler scaffolding workflows.

## [2.10.0] - 2026-06-10

- Added `TelegramCallbackData` for compact, parseable inline keyboard callback payloads with Telegram's 64-byte limit enforced.
- Added immutable `InlineKeyboardMarkup::make()`, `row()`, `button()`, `callback()`, `url()`, and `fromButtons(..., columns: ...)` helpers for building inline keyboards without nested arrays.
- Added additional `InlineKeyboardButton` named constructors and validation for callback data, plus docs, tests, Writerside, README, and Boost updates for the new keyboard ergonomics.

## [2.9.0] - 2026-06-10

- Added `TelegramWebhookReply` and `TelegramWebhookReplyBuilder` for returning Telegram-compatible method payloads directly from synchronous Laravel webhook handlers.
- Added builder helpers for text, photo, document, raw method, typed request, fluent message, and callback-query replies, including `fromUpdate($update)` chat/callback discovery.
- Updated the webhook receiver, tests, README, webhook docs, Writerside docs, and Boost resources to cover reply return values, queued-handler caveats, and file-upload limitations.

## [2.8.0] - 2026-06-10

- Added `text()`, `photo()`, and `document()` outbound shortcut methods on concrete bot clients, the Laravel service wrapper, configured channels, dynamic destinations, the facade, and the Telegram fake.
- Covered shortcut sends through configured channels, dynamic bot tokens, default destinations, HTTP client requests, and fake assertions.
- Documented shortcut usage while keeping the fluent `TelegramMessage` builder, typed request DTOs, and raw `call(method, parameters)` API available for advanced payloads and newly released Telegram methods.

## [2.7.3] - 2026-06-10

- Added a modern Business and monetization cookbook for Business connections, Business messages, managed bot tokens/access settings, Stars subscriptions, paid media, suggested posts, guest replies, and raw `call()` fallbacks.
- Documented operational risks for managed bot token storage, Business/payment identifiers, Telegram-side permission requirements, and testing limitations for real Stars, paid media, and Business capabilities.
- Updated README, Writerside overview, Boost resources, and payments guide tests so modern Telegram Business, managed bot, Stars, and paid media workflows are easier to discover.

## [2.7.2] - 2026-06-10

- Added scenario-first production recipes for operations alerts, ecommerce order updates, support intake, and admin-channel notifications.
- Documented dynamic bot and destination routing, conversation wizard handoff flows, admin callback buttons, and fake-testing assertions for each common scenario.
- Updated README, Writerside docs, Boost resources, and documentation tests so new users can start from practical Laravel bot workflows instead of API primitives only.

## [2.7.1] - 2026-06-10

- Expanded outbound reliability recipes for queued Laravel delivery with unique jobs, backoff, failed-job visibility, Telegram `retry_after`, migrated chat recovery, and local rate-limit backoff.
- Updated the copy-ready `SendTelegramAlert` job to handle `TelegramBotRateLimitException::availableIn()`, expose retry/backoff settings, avoid duplicate alert jobs, and log failures without message text.
- Documented SDK `retry`, local `rate_limit`, idempotency keys, failed-job monitoring, and `TelegramBot::fake()` assertions across README, Markdown docs, Writerside docs, and Boost resources.

## [2.7.0] - 2026-06-10

- Added `TelegramHumanHandoff` as an optional Laravel handoff contract for pausing automation, storing support context in conversation state, and notifying private operator chats.
- Added typed operator message helpers, workflow open/restore/close helpers, and tests covering context restoration, state transitions, and safe operator payload generation.
- Documented forwarding context to operator chats, closing handoffs, resuming automation, queue usage, privacy boundaries, and security guidance across README, Markdown docs, Writerside docs, and Boost resources.

## [2.6.0] - 2026-06-10

- Added `TelegramConversationWizard`, `TelegramConversationStep`, and `TelegramConversationWizardResult` for Laravel-friendly multi-step forms on top of existing conversation workflows.
- Added wizard support for prompts, typed stored step values, validation callbacks, `/cancel`, `/back`, resume behavior, timeout handling, and callback-query data transitions.
- Updated conversation tests, Laravel examples, README, Markdown docs, Writerside docs, and Boost guidance with profile setup, order intake, and support request collection patterns.

## [2.5.0] - 2026-06-10

- Added `TelegramDeepLink` helpers for bot `/start`, `startgroup`, Mini App `startapp`, named Mini App, and attachment-menu `startattach` links with Telegram payload validation.
- Added `TelegramStartPayloadSigner` and `TelegramSignedStartPayload` for compact signed start parameters with optional TTL verification, tamper rejection, and Laravel DI support.
- Documented referral, onboarding, support, Mini App start parameter, webhook verification, README, Writerside, and Boost usage patterns.

## [2.4.0] - 2026-06-10

- Added the fluent `Outbound\TelegramMessage` builder for common text, photo, and document sends without requiring low-level arrays at call sites.
- Added `send(TelegramMessage::...)` support on concrete bot clients, configured channels, dynamic destinations, the Laravel service wrapper, and the Telegram fake.
- Documented facade, DI, manager, client, dynamic token, channel, and testing examples while keeping typed request DTOs and raw `call(method, parameters)` available as lower-level escape hatches.

## [2.3.0] - 2026-06-10

- Added `TelegramMiniAppInitDataValidator` for server-side validation of `Telegram.WebApp.initData` using Telegram's official bot-token HMAC flow, constant-time hash comparison, and optional `auth_date` freshness checks.
- Added `TelegramMiniAppInitData`, `TelegramMiniAppUserData`, and `TelegramMiniAppChatData` accessors for common Mini App user, chat, query, start parameter, and freshness fields.
- Registered the Mini Apps validator in Laravel DI and documented controller, framework-agnostic, runtime tenant token, Writerside, README, and Boost usage.

## [2.2.0] - 2026-06-10

- Added dynamic bot token routing through `botToken()`, `to()`, and configured channel token overrides so runtime code can send to any explicit Telegram `chat_id` without mutating global package config.
- Added Laravel notification routing support for dynamic `token`/`bot_token` values, including `TelegramNotificationMessage::botToken()` and fake assertions that avoid leaking tokens in recorded calls.
- Documented dynamic facade, DI, notification, and Boost usage paths while retaining configured bots, configured channels, typed helpers, and the raw `call(method, parameters)` API.

## [2.1.4] - 2026-06-03

- Updated the end-to-end setup guide to prefer the package `telegram-bot:me` command for bot identity checks while keeping raw Telegram `getMe` as a fallback.
- Clarified that forum `message_thread_id` and direct messages topic IDs are optional routing refinements, not required channel setup steps.

## [2.1.3] - 2026-06-03

- Cleaned up the next Qodana SARIF layer across generator helpers, webhook dispatcher configuration checks, result DTO reflection, conversation keys, notification routing, and retry policy parameters.
- Extracted shared bot configuration resolution so the Laravel config validator and runtime manager use the same implementation.
- Updated the Laravel example webhook route to avoid IDE-only macro false positives while keeping the package route macro documented and tested.

## [2.1.2] - 2026-06-03

- Hardened generated Telegram request/result registries to emit package class references as `::class` constants instead of raw class-string literals.
- Cleaned up confirmed Qodana SARIF findings around readonly promoted properties, boolean config checks, cache resolver visibility, dispatcher route metadata types, release-note parsing, and unused example parameters.
- Added a self-contained example test base for the Laravel demo tests and kept generated schema output reproducible through the API schema generator.

## [2.1.1] - 2026-06-03

- Raised PHPStan analysis from level 5 to level 8 and fixed the newly exposed iterable, nullable-string, and mixed-return findings without suppressions.
- Hardened Laravel console option normalization, cache repository resolution, facade method PHPDoc, fake payload assertions, and generated schema helper contracts.
- Added an explicit string-key payload guard to typed Telegram request DTO payload builders.

## [2.1.0] - 2026-06-03

- Added Telegram domain enums for chat actions, chat types, update types, poll types, sticker types/formats, menu buttons, message entities, inline query result types, paid media types, and bot command scope types.
- Expanded generated request builders with enum-aware bindings for parse modes, chat actions, poll types, sticker types/formats, and allowed update types.
- Added typed nested input DTOs for link previews, reply parameters, suggested post parameters/prices, inline keyboard buttons, and inline keyboard markup.
- Added enum accessors to common Telegram result DTOs and completed the generated result map for `sendChatAction`, `sendGift`, and `sendMessageDraft`.
- Added `TelegramBotLaravelConfig` for typed Laravel config access and reused it in `telegram-bot:doctor` validation.
- Hardened README, Writerside, Boost guidance, examples, and documentation tests against returning to magic-string parse-mode snippets.

## [2.0.3] - 2026-06-03

- Added `TelegramParseMode` enum support to notification messages, common typed message DTOs, and generated request builders.
- Normalized backed enums in request payloads so enum-backed parameters serialize to Telegram-compatible scalar values.
- Updated README, documentation, Writerside, Boost skill, tests, and examples to avoid hardcoded parse-mode strings in favor of typed constants and enums.

## [2.0.2] - 2026-06-03

- Moved selected validation paths onto PHP 8.4 `array_any`, `array_all`, and `array_find` helpers.
- Expanded `#[Override]` guards across package service-provider registration, test doubles, and example webhook handlers.
- Extended PHP 8.4 strictness coverage so key production validation paths keep using PHP 8.4 array helpers.

## [2.0.1] - 2026-06-03

- Added PHP 8.4 strictness hardening with typed class constants across generated request DTOs, schema registries, webhook update metadata, and Laravel internals.
- Added `#[Override]` guards to DTO serialization methods and explicit package contract implementations.
- Added regression coverage to require typed class constants for all source classes.

## [2.0.0] - 2026-06-03

- Raised the package baseline to PHP `^8.4` and Laravel/Illuminate `^13.0` only.
- Ended compatibility with Laravel 12 and PHP 8.2/8.3; the 1.x line is capped at `v1.19.1`.
- Updated CI, documentation, and release policy coverage for the new major-version support policy.

## [1.19.1] - 2026-06-03

- Added a GitHub release notes generation command that renders the current changelog entry into release-ready markdown.

## [1.19.0] - 2026-06-03

- Added generated IDE-friendly request builders for all 176 Telegram Bot API methods, plus request/result registries and schema checksums.
- Expanded typed response mapping and DTO coverage for common API result objects including invite links, forum topics, stickers, gifts, stars, profile photos, menus, commands, and bot metadata.
- Added conversation workflow APIs with typed context accessors, guarded transitions, timeouts, reset support, and update-scoped workflows.
- Added webhook router v2 support for per-route middleware, grouped handlers, update-type fallbacks, and attribute discovery.
- Added optional retry, local rate limiting, and sanitized API observability events for Laravel-resolved clients.
- Expanded the fake testing DSL with typed payload, sequence, token leakage, webhook update, and conversation assertions.
- Expanded Laravel examples and cookbook documentation for demo bots, payments stubs, conversations, middleware, testing, and production bot recipes.
- Added release readiness and Packagist verification scripts.

## [1.18.2] - 2026-06-03

- Addressed Qodana SARIF findings for the Telegram fake channel constructor, duplicated cache repository resolution, readonly Laravel services, redundant casts/interpolation, facade imports, and documentation signatures.
- Tuned the local Qodana configuration to exclude noisy inspections that conflict with deliberate SDK and Laravel container patterns while keeping the zero-problem quality gate.

## [1.18.1] - 2026-06-03

- Restored the local `qodana.yaml` configuration for manual Qodana analysis without restoring the paid GitHub Qodana workflow.

## [1.18.0] - 2026-06-03

- Added a generated `TelegramBotApiMethodSchema` and method-scoped `TelegramBotRequestData::forMethod()` coverage for all 176 Bot API 10.0 methods and 863 documented parameters.
- Expanded typed responses with a generic `TelegramBotResultData` fallback for unmapped Telegram objects and lists while preserving raw `call()` behavior.
- Added a Laravel webhook middleware pipeline and cache-backed `TelegramConversationManager` for stateful webhook flows.
- Added Telegram API schema generator tooling, documentation, Writerside updates, Laravel Boost guidance, and release coverage for the new layers.

## [1.17.0] - 2026-06-03

- Added an opt-in typed response layer through `callData()` and common `*Data()` response helpers while preserving raw Telegram `result` returns from existing methods.
- Added typed response DTOs for Telegram `File` and `WebhookInfo` and made `TelegramWebhookUpdate` implement the shared data DTO contract.
- Added typed response documentation, Writerside topic, Laravel Boost guidance, and fake coverage for typed response helpers.

## [1.16.0] - 2026-06-03

- Added a Laravel notification channel with `TelegramNotificationMessage` for model and on-demand Telegram notifications.
- Added notification payload support for strings, explicit method arrays, typed request DTOs with method inference, configured package channels, named bots, forum topics, and direct messages topics.
- Added notification documentation, Writerside topic, Laravel Boost guidance, and a copy-ready Laravel notification example.

## [1.15.0] - 2026-06-03

- Added typed outbound DTO builders for common message workflows: `SendMessageData`, `EditMessageTextData`, `SendPhotoData`, `SendDocumentData`, and `AnswerCallbackQueryData`.
- Added webhook observability events for handled, failed, queued, and duplicate-skipped updates.
- Added production recipes and copy-ready Laravel examples for command handlers, callback handlers, outbound retry/rate-limit recovery, metrics listeners, and manual webhook routes.

## [1.14.0] - 2026-06-03

- Added optional queued webhook processing through `TelegramWebhookJob`, including queue connection/name/after-commit configuration.
- Added cache-backed webhook idempotency for duplicate Telegram `update_id` deliveries per bot.
- Documented production webhook queue and duplicate-update guard configuration in README, Writerside, Markdown docs, and Laravel Boost resources.

## [1.13.0] - 2026-06-03

- Added a Laravel webhook dispatcher for command handlers, update-type handlers, fallback handlers, and manual webhook route registration through `Route::telegramBotWebhook()`.
- Added `TelegramWebhookCommand`, `TelegramWebhookCommandHandler`, and `TelegramBot::fake()` with bot/channel-aware assertions for Laravel application tests.
- Removed the Qodana workflow/configuration path from the package because real GitHub enforcement requires paid Qodana Cloud access, and declared the `ext-openssl` runtime requirement used by Passport decryption.

## [1.12.3] - 2026-06-02

- Added a dedicated Qodana GitHub Actions workflow with path filters, concurrency, pull request support, and a safe skip path when `QODANA_TOKEN` is not configured.

## [1.12.2] - 2026-06-02

- Added a committed Qodana configuration using the PHP linter, the recommended inspection profile, Composer bootstrap, PHP 8.2 compatibility analysis, and a zero-problem quality gate.

## [1.12.1] - 2026-06-02

- Addressed Qodana inspection findings for binary-safe file streams, JSON decoding with `JSON_THROW_ON_ERROR`, class constants, condition ordering, and redundant casts without changing public behavior.

## [1.12.0] - 2026-06-02

- Added typed inbound webhook DTO accessors for the remaining official Telegram update families: business connections, deleted business messages, purchased paid media, polls, poll answers, message reactions, chat boosts, removed chat boosts, and managed bot updates.
- Added typed DTOs for common boost, poll, reaction, business, and managed bot update objects while keeping raw update payload access available.
- Updated webhook, payments, README, Writerside, and Laravel Boost documentation with the expanded typed inbound update surface.

## [1.11.3] - 2026-06-02

- Reduced GitHub Actions usage by keeping Composer checks off documentation-only pushes and simplifying the Writerside workflow to a single documentation generation and Pages deploy job.

## [1.11.2] - 2026-06-02

- Opted the Writerside documentation workflow into GitHub Actions Node.js 24 execution for the remaining JetBrains action while keeping the current published JetBrains action major.

## [1.11.1] - 2026-06-02

- Updated Laravel Boost package guidelines and the package skill with the current typed webhook DTO accessors and Telegram API exception helpers.
- Updated GitHub Actions workflow action majors used by Composer checks and Writerside documentation deployment to current Node.js 24-compatible releases where available.

## [1.11.0] - 2026-06-02

- Added typed DTOs for common nested Telegram objects: message entities, photo sizes, documents, successful payments, order info, and chat members.
- Added non-breaking typed accessors for message media, message entities, successful payments, pre-checkout order info, and chat member status payloads while preserving the existing raw array helpers.
- Updated webhook, payments, and README examples to show the deeper typed object accessors.

## [1.10.0] - 2026-06-02

- Added typed inbound webhook DTO accessors for inline queries, chosen inline results, shipping queries, pre-checkout queries, chat member updates, and chat join requests.
- Expanded `TelegramMessageData` with common message sub-object accessors for replies, media, entities, successful payments, Passport data, games, live photos, and guest metadata.
- Added `retryAfter()` and `migrateToChatId()` helpers to `TelegramBotApiException`.
- Added common webhook handler documentation for message commands, callback buttons, pre-checkout handling, and chat member updates.

## [1.9.0] - 2026-06-02

- Added `TelegramCallbackQueryData` and `TelegramWebhookUpdate::callbackQuery()` for typed inline keyboard callback handling in webhook handlers.
- Added unit and Laravel webhook receiver coverage for typed callback query accessors.

## [1.8.3] - 2026-06-02

- Updated the Composer checks workflow to `actions/checkout@v5` to avoid the Node.js 20 action runtime deprecation warning.

## [1.8.2] - 2026-06-02

- Added a GitHub Actions Composer checks workflow for package validation, static analysis, Telegram API surface checks, and PHPUnit coverage.
- Expanded the README badge row with tests, current Packagist stable version, total downloads, license, and required PHP version badges.

## [1.8.1] - 2026-06-02

- Switched the README Packagist badge to the Packagist `poser.pugx.org` latest-stable badge.

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
