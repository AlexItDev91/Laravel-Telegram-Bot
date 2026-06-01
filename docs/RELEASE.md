# Release Process

Every package update must include a version bump and a git tag.

The package starts at `1.0.0`. Packagist reads the package version from git tags, so do not hardcode a `version` field in `composer.json`.

## Version Bump Rules

- Patch bump: small compatible changes, bug fixes, documentation updates, tests, dependency constraint cleanup, internal refactoring, and non-breaking maintenance.
- Minor bump: significant compatible changes, new public features, new SDK behavior, new Laravel integration behavior, or Telegram Bot API surface expansions.
- Major bump: breaking public API, config, behavior, namespace, dependency, or Laravel compatibility changes.

Examples:

- `1.0.0` to `1.0.1`: documentation fix or internal bug fix.
- `1.0.0` to `1.1.0`: new compatible SDK feature.
- `1.0.0` to `2.0.0`: breaking method signature or config change.

## Required Files

For every release commit:

1. Update `VERSION`.
2. Update `CHANGELOG.md`.
3. Commit the change.
4. Create a git tag.
5. Push the commit and tag.

## Commands

Patch release:

```bash
printf "1.0.1\n" > VERSION
```

Minor release:

```bash
printf "1.1.0\n" > VERSION
```

Commit:

```bash
git add VERSION CHANGELOG.md
git commit -m "Release v1.0.1"
```

Create an annotated tag:

```bash
git tag -a v1.0.1 -m "Release v1.0.1"
```

Push:

```bash
git push origin main
git push origin v1.0.1
```

## Pre-Release Checks

Run before tagging:

```bash
composer validate --no-check-publish --no-interaction
composer test
composer test:coverage-surface
```

When the release changes Laravel integration, also run focused integration tests in the host Laravel application.
