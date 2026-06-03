#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$version = trim((string) file_get_contents($root.'/VERSION'));
$changelog = (string) file_get_contents($root.'/CHANGELOG.md');
$composer = json_decode((string) file_get_contents($root.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

failIf($version === '', 'VERSION must not be empty.');
failIf(preg_match('/^\d+\.\d+\.\d+$/', $version) !== 1, "VERSION [$version] is not a semantic version.");
failIf(! str_contains($changelog, "## [$version] - "), "CHANGELOG.md does not contain an entry for [$version].");
failIf(is_array($composer) && array_key_exists('version', $composer), 'composer.json must not contain a hardcoded version field.');

$tag = 'v'.$version;
$existingTag = trim((string) shell_exec('git tag --list '.escapeshellarg($tag)));

if ($existingTag !== '') {
    $tagCommit = trim((string) shell_exec('git rev-list -n 1 '.escapeshellarg($tag)));
    $head = trim((string) shell_exec('git rev-parse HEAD'));

    failIf($tagCommit !== $head, "Tag [$tag] already exists and does not point to HEAD.");
}

echo "Release readiness OK for $tag.\n";

function failIf(bool $condition, string $message): void
{
    if (! $condition) {
        return;
    }

    fwrite(STDERR, $message."\n");
    exit(1);
}
