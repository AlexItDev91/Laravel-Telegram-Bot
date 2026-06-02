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
| `.github/workflows/writerside.yml` | GitHub Actions build, check, and GitHub Pages deploy workflow. |

Keep `docs/*.md` and `Writerside/topics/*.md` synchronized when changing public documentation.
Large reference topics can be re-imported from the matching `docs/*.md` file when the content is intentionally identical.

## Writerside Deployment

The GitHub Actions workflow builds the `Writerside/tg` instance with JetBrains Writerside Docker builder `2026.04.8711`.
It uploads the generated website archive, checks the Writerside build report, and deploys the unpacked static site to GitHub Pages.

GitHub repository settings must use:

| Setting | Value |
| --- | --- |
| Pages source | `GitHub Actions` |
| Workflow branch | `main` |

No package token, bot token, webhook secret, or Algolia secret is required by the current workflow.

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
```

When Telegram adds, changes, renames, or deprecates methods, objects, fields, parameters, webhook behavior, file behavior, payments, Mini Apps, or update types, update all relevant areas together:

| Area | Expected update |
| --- | --- |
| SDK method registry and native helpers | New or changed Telegram methods are exposed. |
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
