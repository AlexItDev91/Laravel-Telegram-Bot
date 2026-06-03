<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ReleasePolicyTest extends TestCase
{
    public function test_package_has_current_stable_version_and_release_policy(): void
    {
        $version = trim((string) file_get_contents(__DIR__.'/../../VERSION'));
        $changelog = file_get_contents(__DIR__.'/../../CHANGELOG.md');
        $agents = file_get_contents(__DIR__.'/../../AGENTS.md');
        $readme = file_get_contents(__DIR__.'/../../README.md');

        $this->assertSame('1.19.0', $version);
        $this->assertIsString($changelog);
        $this->assertIsString($agents);
        $this->assertIsString($readme);
        $this->assertFileDoesNotExist(__DIR__.'/../../docs/RELEASE.md');

        foreach ([
            'Every package update must include a version bump and a git tag',
            'complete the release workflow automatically unless the user explicitly says not to commit, tag, or push',
            'Stage only task-related files',
            'Push the current branch',
            'composer analyse',
            'composer check:telegram-api-surface',
            'Patch bump',
            'Minor bump',
            'git tag -a v1.0.1 -m "Release v1.0.1"',
            'git push origin v1.0.1',
        ] as $requiredReleaseInstruction) {
            $this->assertStringContainsString($requiredReleaseInstruction, $agents);
        }

        $this->assertStringContainsString('## [1.19.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.18.2] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.18.1] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.18.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.17.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.16.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.15.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.14.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.13.0] - 2026-06-03', $changelog);
        $this->assertStringContainsString('## [1.12.3] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.12.2] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.12.1] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.12.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.11.3] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.11.2] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.11.1] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.11.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.10.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.9.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.8.3] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.8.2] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.8.1] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.8.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.7.4] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.7.3] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.7.2] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.7.1] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.7.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.6.0] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.5.4] - 2026-06-02', $changelog);
        $this->assertStringContainsString('## [1.5.3] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.5.2] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.5.1] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.5.0] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.4.0] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.3.2] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.3.1] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.3.0] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.2.0] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.1.0] - 2026-06-01', $changelog);
        $this->assertStringContainsString('## [1.0.1] - 2026-06-01', $changelog);
        $this->assertStringContainsString('Every package update must include a version bump and a git tag.', $agents);
        $this->assertStringContainsString('complete the release workflow automatically unless the user explicitly says not to commit, tag, or push', $agents);
        foreach ([
            '[![Tests](https://github.com/AlexItDev91/Laravel-Telegram-Bot/actions/workflows/tests.yml/badge.svg)](https://github.com/AlexItDev91/Laravel-Telegram-Bot/actions/workflows/tests.yml)',
            '[![Latest Stable Version](https://img.shields.io/packagist/v/alexitdev91/laravel-telegram-bot?label=stable)](https://packagist.org/packages/alexitdev91/laravel-telegram-bot)',
            '[![Total Downloads](https://img.shields.io/packagist/dt/alexitdev91/laravel-telegram-bot)](https://packagist.org/packages/alexitdev91/laravel-telegram-bot)',
            '[![License](https://img.shields.io/packagist/l/alexitdev91/laravel-telegram-bot)](LICENSE)',
            '[![PHP Version Require](https://img.shields.io/packagist/php-v/alexitdev91/laravel-telegram-bot)](composer.json)',
        ] as $badge) {
            $this->assertStringContainsString($badge, $readme);
        }

        $this->assertStringNotContainsString('https://poser.pugx.org/alexitdev91/laravel-telegram-bot/v/stable', $readme);
        $this->assertStringNotContainsString('docs/RELEASE.md', $readme);
    }

    public function test_github_actions_composer_checks_workflow_covers_release_checks(): void
    {
        $workflow = file_get_contents(__DIR__.'/../../.github/workflows/tests.yml');

        $this->assertIsString($workflow);

        foreach ([
            'name: Composer checks',
            'composer validate --no-check-publish --no-interaction',
            'composer analyse',
            'composer check:telegram-api-surface',
            'composer test',
            'composer test:coverage-surface',
            'php: ["8.2", "8.3", "8.4"]',
            'actions/checkout@v6',
        ] as $requiredWorkflowText) {
            $this->assertStringContainsString($requiredWorkflowText, $workflow);
        }

        $this->assertStringNotContainsString('actions/checkout@v4', $workflow);
        $this->assertStringNotContainsString('actions/checkout@v5', $workflow);
        $this->assertStringNotContainsString('"docs/**"', $workflow);
        $this->assertStringNotContainsString('"README.md"', $workflow);
        $this->assertStringNotContainsString('"CHANGELOG.md"', $workflow);
        $this->assertStringNotContainsString('"VERSION"', $workflow);
    }

    public function test_qodana_config_is_restored_without_paid_github_workflow(): void
    {
        $root = dirname(__DIR__, 2);
        $qodana = file_get_contents($root.'/qodana.yaml');

        $this->assertIsString($qodana);
        $this->assertFileDoesNotExist($root.'/.github/workflows/qodana.yml');

        foreach ([
            'qodana.recommended',
            'linter: qodana-php',
            'version: "8.2"',
            'composer install --no-interaction --prefer-dist --no-progress',
            'any: 0',
            'PhpSameParameterValueInspection',
            'PhpUnhandledExceptionInspection',
            'PhpDocMissingThrowsInspection',
            'PhpNotInstalledPackagesInspection',
            'PhpRedundantOptionalArgumentInspection',
            'PhpLoopCanBeConvertedToArrayMapInspection',
        ] as $requiredQodanaConfig) {
            $this->assertStringContainsString($requiredQodanaConfig, $qodana);
        }
    }

    public function test_composer_does_not_hardcode_package_version(): void
    {
        $composer = json_decode((string) file_get_contents(__DIR__.'/../../composer.json'), true, flags: JSON_THROW_ON_ERROR);

        $this->assertIsArray($composer);
        $this->assertArrayNotHasKey('version', $composer);
        $this->assertSame('php scripts/check-release-readiness.php', $composer['scripts']['check:release-readiness'] ?? null);
        $this->assertSame('php scripts/verify-packagist-release.php', $composer['scripts']['verify:packagist-release'] ?? null);
        $this->assertFileExists(__DIR__.'/../../scripts/check-release-readiness.php');
        $this->assertFileExists(__DIR__.'/../../scripts/verify-packagist-release.php');
    }
}
