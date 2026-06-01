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

## Agent Automation Rules

When an agent makes any package update, it must complete the release workflow automatically unless the user explicitly says not to commit, tag, or push.

The agent must:

1. Run the required checks.
2. Stage only task-related files.
3. Commit the full task diff.
4. Create an annotated `v<VERSION>` tag from the committed version in `VERSION`.
5. Push the current branch.
6. Push the tag.

The agent must not leave a completed package update, version bump, or changelog entry uncommitted. If commit, tag, or push fails, the agent must report the exact failed step and reason. Unrelated dirty files must remain unstaged.

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
composer analyse
composer check:telegram-api-surface
composer test
composer test:coverage-surface
```

When the release changes Laravel integration, also run focused integration tests in the host Laravel application.
