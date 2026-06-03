#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$version = $argv[1] ?? trim((string) file_get_contents($root.'/VERSION'));
$version = ltrim($version, 'v');
$tag = 'v'.$version;
$package = 'alexitdev91/laravel-telegram-bot';

failIf(preg_match('/^\d+\.\d+\.\d+$/', $version) !== 1, "Version [$version] is not a semantic version.");

$output = [];
$exitCode = 0;
exec('composer show '.escapeshellarg($package).' --all 2>&1', $output, $exitCode);

failIf($exitCode !== 0, "composer show failed:\n".implode("\n", $output));

$text = implode("\n", $output);

failIf(! str_contains($text, $tag), "Packagist does not list [$tag] for [$package].");
failIf(! str_contains($text, 'source'), 'Packagist output does not contain source metadata.');

echo "Packagist release OK for $package $tag.\n";

function failIf(bool $condition, string $message): void
{
    if (! $condition) {
        return;
    }

    fwrite(STDERR, $message."\n");
    exit(1);
}
