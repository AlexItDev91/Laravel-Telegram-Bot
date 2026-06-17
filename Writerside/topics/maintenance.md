# Maintenance

This page is for package maintainers who update the SDK, release notes, and documentation.

## Documentation Layout

| Path | Purpose |
| --- | --- |
| `README.md` | Public package entry point for Packagist and GitHub. |
| `docs/*.md` | Markdown source guides kept for direct repository browsing. |
| `Writerside/writerside.cfg` | Writerside module configuration. |
| `Writerside/tg.tree` | Writerside table of contents. |
| `Writerside/topics/*.md` | Published documentation website topics. |
| `.github/workflows/writerside.yml` | GitHub Actions documentation generation and GitHub Pages deploy workflow. |

Keep `docs/*.md` and `Writerside/topics/*.md` synchronized when changing public documentation.
Large reference topics can be re-imported from the matching `docs/*.md` file when the content is intentionally identical.

## Writerside Deployment

The GitHub Actions workflow generates the `Writerside/tg` instance with JetBrains Writerside Docker builder `2026.04.8711`.
It unpacks the generated website archive, adds a static browser-side search index, and deploys the static site to GitHub Pages.

GitHub repository settings must use:

| Setting | Value |
| --- | --- |
| Pages source | `GitHub Actions` |
| Workflow branch | `main` |

No package token, bot token, webhook secret, paid static-analysis token, Algolia account, or external search service is required by the current documentation workflow.

## Published Search

Published documentation search is fully static.
The workflow runs `scripts/build-writerside-static-search.php` after unpacking the Writerside website archive.
That script reads `Writerside/tg.tree` and `Writerside/topics/*.md`, writes `local-search-index.json`, copies `local-search.js`, and injects the script into generated HTML pages before the GitHub Pages artifact is uploaded.

Keep the static search implementation dependency-free.
Do not add external search services or committed generated search indexes.

## Qodana Configuration

The repository keeps `qodana.yaml` for local or manually triggered Qodana analysis.
There is no `.github/workflows/qodana.yml` workflow in the current package release, so GitHub Actions does not require a paid Qodana Cloud token.
The config keeps a zero-problem quality gate and excludes only noisy inspections that conflict with deliberate SDK patterns, such as fixed-key DTO accessors, Laravel container exception flow, and environment-specific package installation checks.

## Local Editing

Open the repository in a JetBrains IDE with the Writerside plugin installed.
The module is `Writerside`, and the instance ID is `tg`.

For local preview, use the Writerside Preview tool window.
For local web archive builds, use Writerside's export action for the `tg` instance.

## API Currency

Before SDK API-surface work, check:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Telegram Bot API changelog](https://core.telegram.org/bots/api-changelog)

Then run:

```bash
composer check:telegram-api-surface
composer generate:telegram-api-schema
```

`composer check:telegram-api-surface` verifies that the public method enum, native helpers, Markdown method reference, and Writerside method reference still match the documented Bot API methods.
`composer generate:telegram-api-schema` refreshes the generated `TelegramBotApiMethodSchema` used by method-scoped request DTOs such as `TelegramBotRequestData::forMethod()`.

When Telegram adds, changes, renames, or deprecates methods, objects, fields, parameters, webhook behavior, file behavior, payments, Mini Apps, or update types, update all relevant areas together:

| Area | Expected update |
| --- | --- |
| SDK method registry and native helpers | New or changed Telegram methods are exposed. |
| Generated method schema | Method-scoped request DTOs know the current method parameters and required fields. |
| DTOs and validation | High-risk structured payloads remain correct. |
| Tests | Surface, behavior, and regression coverage stays current. |
| `docs/*.md` | Repository Markdown docs stay accurate. |
| `Writerside/topics/*.md` | Published site stays accurate. |
| `CHANGELOG.md` and `VERSION` | Release metadata matches the change. |

## Release Checks

Run the package checks before publishing a release:

```bash
composer validate --no-check-publish --no-interaction
composer analyse
composer check:telegram-api-surface
composer test
composer test:coverage-surface
```

For documentation-only changes, the full test suite still protects version policy, documentation expectations, and API-surface drift.

## Versioning

| Change type | Version bump |
| --- | --- |
| Documentation, tests, small compatible fixes, dependency constraint cleanup | Patch |
| Significant compatible features, new SDK behavior, Laravel integration behavior, or Telegram API surface expansion | Minor |
| Breaking public API, config, behavior, namespace, dependency, or Laravel compatibility change | Major |

Packagist reads versions from Git tags.
Do not add a hardcoded `version` field to `composer.json`.
