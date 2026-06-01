# Telegram Bot SDK Agent Rules

## Output

- Keep package documentation and public code comments in English unless a task explicitly asks otherwise.
- Keep code, commands, file paths, identifiers, API names, logs, errors, and quotes in their original language.

## Scope

- This package provides a Laravel-friendly PHP SDK for the official Telegram Bot API.
- Keep the core client framework-agnostic where practical.
- Laravel integration belongs in the `AlexItDev91\LaravelTelegramBot\Laravel` and `AlexItDev91\LaravelTelegramBot\Facades` namespaces.
- Do not store bot tokens, chat IDs, webhook secrets, or other credentials in committed files.

## API Currency

Before any implementation, bugfix, release preparation, or API-surface change, check the current official Telegram Bot API documentation and changelog:

- https://core.telegram.org/bots/api
- https://core.telegram.org/bots/api-changelog

Do not assume the local SDK is current.

When Telegram adds, changes, renames, or deprecates methods, objects, fields, parameters, webhook behavior, file behavior, payments, Mini Apps, or update types, update the SDK surface, tests, docs, and release notes as part of the task.

The SDK must always keep a raw `call(method, parameters)` API so newly released Telegram methods are usable before typed helpers are added.

## Versioning

- Every package update must include a version bump and a git tag.
- The current version is stored in `VERSION`.
- Release notes are stored in `CHANGELOG.md`.
- If a task changes package code, public documentation, tests, release notes, or package behavior, the agent must complete the release workflow automatically unless the user explicitly says not to commit, tag, or push.
- Automatic release completion means: run the required checks, stage only task-related files, commit the full task diff, create an annotated `v<VERSION>` tag, push the current branch, and push the tag.
- Do not leave a version bump, changelog entry, or completed package change uncommitted at the end of the task unless commit, tag, or push fails. If any git step fails, report the exact failed step and reason.
- Never stage or commit unrelated user changes. If unrelated dirty files exist, leave them unstaged and commit only the task-related files.
- Patch bump: small compatible changes, bug fixes, documentation, tests, dependency constraint cleanup, internal refactoring, and non-breaking maintenance.
- Minor bump: significant compatible changes, new public features, new SDK behavior, new Laravel integration behavior, or Telegram Bot API surface expansions.
- Major bump: breaking public API, config, behavior, namespace, dependency, or Laravel compatibility changes.
- Packagist reads versions from git tags, so do not add a hardcoded `version` field to `composer.json`.

Release workflow for agents:

1. Run the required checks:

```bash
composer validate --no-check-publish --no-interaction
composer analyse
composer check:telegram-api-surface
composer test
composer test:coverage-surface
```

2. Stage only task-related files.
3. Commit the full task diff.
4. Create an annotated tag from `VERSION`:

```bash
git tag -a v1.0.1 -m "Release v1.0.1"
```

5. Push the current branch.
6. Push the tag:

```bash
git push origin v1.0.1
```

When the release changes Laravel integration, also run focused integration tests in the host Laravel application.

## Development

- Prefer minimal production-ready changes.
- Do not add dependencies unless the task requires them and the reason is documented.
- Use the official Telegram Bot API response contract: successful responses contain `ok: true` and `result`; failed responses contain `ok: false`, `description`, and may contain `error_code` and `parameters`.
- Keep Telegram user, chat, and message identifiers 64-bit safe. Do not store them as 32-bit integers.
- Cover new SDK behavior with tests before or alongside implementation.
- Run focused package/application tests after changes.
