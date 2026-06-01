<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ReleasePolicyTest extends TestCase
{
    public function test_package_has_current_stable_version_and_release_policy(): void
    {
        $version = trim((string) file_get_contents(__DIR__.'/../../VERSION'));
        $changelog = file_get_contents(__DIR__.'/../../CHANGELOG.md');
        $releaseGuide = file_get_contents(__DIR__.'/../../docs/RELEASE.md');
        $agents = file_get_contents(__DIR__.'/../../AGENTS.md');
        $readme = file_get_contents(__DIR__.'/../../README.md');

        $this->assertSame('1.0.1', $version);
        $this->assertIsString($changelog);
        $this->assertIsString($releaseGuide);
        $this->assertIsString($agents);
        $this->assertIsString($readme);

        foreach ([
            'Every package update must include a version bump and a git tag',
            'Patch bump',
            'Minor bump',
            'git tag -a v1.0.1 -m "Release v1.0.1"',
            'git push origin v1.0.1',
        ] as $requiredReleaseInstruction) {
            $this->assertStringContainsString($requiredReleaseInstruction, $releaseGuide);
        }

        $this->assertStringContainsString('## [1.0.1] - 2026-06-01', $changelog);
        $this->assertStringContainsString('Every package update must include a version bump and a git tag.', $agents);
        $this->assertStringContainsString('[docs/RELEASE.md](docs/RELEASE.md)', $readme);
    }

    public function test_composer_does_not_hardcode_package_version(): void
    {
        $composer = json_decode((string) file_get_contents(__DIR__.'/../../composer.json'), true, flags: JSON_THROW_ON_ERROR);

        $this->assertIsArray($composer);
        $this->assertArrayNotHasKey('version', $composer);
    }
}
