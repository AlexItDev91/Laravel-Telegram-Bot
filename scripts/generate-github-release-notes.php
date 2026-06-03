#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$version = $argv[1] ?? trim((string) file_get_contents($root.'/VERSION'));
$version = ltrim($version, 'v');
$outputPath = $argv[2] ?? null;
$changelog = (string) file_get_contents($root.'/CHANGELOG.md');

failIf(preg_match('/^\d+\.\d+\.\d+$/', $version) !== 1, "Version [$version] is not a semantic version.");

$entry = changelogEntry($changelog, $version);
$notes = "# v$version\n\n".$entry."\n";

if (is_string($outputPath) && $outputPath !== '') {
    $directory = dirname($outputPath);

    if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
        fwrite(STDERR, "Failed to create release notes directory [$directory].\n");
        exit(1);
    }

    if (file_put_contents($outputPath, $notes) === false) {
        fwrite(STDERR, "Failed to write GitHub release notes to [$outputPath].\n");
        exit(1);
    }

    echo "GitHub release notes written to $outputPath for v$version.\n";

    exit(0);
}

echo $notes;

function changelogEntry(string $changelog, string $version): string
{
    $pattern = '/^## \['.preg_quote($version, '/').'] - [^\n]+\R(?P<body>.*?)(?=^## \[|\z)/ms';

    if (preg_match($pattern, $changelog, $match) !== 1) {
        fwrite(STDERR, "CHANGELOG.md does not contain an entry for [$version].\n");
        exit(1);
    }

    $entry = trim($match['body']);

    if ($entry === '') {
        fwrite(STDERR, "CHANGELOG.md entry for [$version] is empty.\n");
        exit(1);
    }

    return $entry;
}

function failIf(bool $condition, string $message): void
{
    if (! $condition) {
        return;
    }

    fwrite(STDERR, $message."\n");
    exit(1);
}
