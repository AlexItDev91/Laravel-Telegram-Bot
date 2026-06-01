#!/usr/bin/env php
<?php

declare(strict_types=1);

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodRegistry;

require __DIR__.'/../vendor/autoload.php';

$apiUrl = 'https://core.telegram.org/bots/api';
$changelogUrl = 'https://core.telegram.org/bots/api-changelog';
$html = fetchOfficialPage($apiUrl);
$changelogHtml = fetchOfficialPage($changelogUrl);
[$officialVersion, $officialReleaseDate] = latestRelease($html, $apiUrl);
[$changelogVersion, $changelogReleaseDate] = latestRelease($changelogHtml, $changelogUrl);
$officialMethods = officialMethodNames($html);
$localMethods = array_map(
    static fn (TelegramBotApiMethod $method): string => $method->value,
    TelegramBotApiMethod::cases(),
);
$officialUpdateTypes = officialUpdateTypes($html);
$localUpdateTypes = localUpdateTypes();

$failures = [];

if ($officialVersion !== TelegramBotApiMethodRegistry::BOT_API_VERSION) {
    $failures[] = sprintf(
        'Bot API version mismatch: official [%s], local [%s].',
        $officialVersion,
        TelegramBotApiMethodRegistry::BOT_API_VERSION,
    );
}

if ($officialReleaseDate !== TelegramBotApiMethodRegistry::BOT_API_RELEASE_DATE) {
    $failures[] = sprintf(
        'Bot API release date mismatch: official [%s], local [%s].',
        $officialReleaseDate,
        TelegramBotApiMethodRegistry::BOT_API_RELEASE_DATE,
    );
}

if ($changelogVersion !== $officialVersion || $changelogReleaseDate !== $officialReleaseDate) {
    $failures[] = sprintf(
        'Bot API changelog mismatch: API page [%s on %s], changelog [%s on %s].',
        $officialVersion,
        $officialReleaseDate,
        $changelogVersion,
        $changelogReleaseDate,
    );
}

foreach (diffLists('method', $officialMethods, $localMethods) as $failure) {
    $failures[] = $failure;
}

foreach (diffLists('update type', $officialUpdateTypes, $localUpdateTypes) as $failure) {
    $failures[] = $failure;
}

if ($failures !== []) {
    foreach ($failures as $failure) {
        fwrite(STDERR, $failure."\n");
    }

    exit(1);
}

printf(
    "Telegram Bot API surface is current: version %s released on %s, %d methods, %d update types.\n",
    $officialVersion,
    $officialReleaseDate,
    count($localMethods),
    count($localUpdateTypes),
);

function fetchOfficialPage(string $url): string
{
    $context = stream_context_create([
        'http' => [
            'timeout' => 15,
        ],
    ]);

    $html = file_get_contents($url, false, $context);

    if ($html === false) {
        fwrite(STDERR, "Failed to fetch {$url}.\n");
        exit(1);
    }

    return $html;
}

/**
 * @return array{0: string, 1: string}
 */
function latestRelease(string $html, string $url): array
{
    $text = html_entity_decode(strip_tags($html));

    if (! preg_match('/([A-Z][a-z]+ \d{1,2}, \d{4})\s+Bot API\s+([0-9.]+)/', $text, $release)) {
        fwrite(STDERR, "Failed to detect the latest Telegram Bot API release from {$url}.\n");
        exit(1);
    }

    $releaseDate = DateTimeImmutable::createFromFormat('F j, Y', $release[1]);

    if (! $releaseDate instanceof DateTimeImmutable) {
        fwrite(STDERR, "Failed to parse Telegram Bot API release date [{$release[1]}].\n");
        exit(1);
    }

    return [$release[2], $releaseDate->format('Y-m-d')];
}

/**
 * @return list<string>
 */
function officialMethodNames(string $html): array
{
    preg_match_all('/<h4><a class="anchor" name="([^"]+)"[^>]*>.*?<\/a>(.*?)<\/h4>/s', $html, $matches, PREG_SET_ORDER);

    $methods = [];

    foreach ($matches as $match) {
        $title = trim(strip_tags($match[2]));

        if (preg_match('/^[a-z][A-Za-z0-9]*$/', $title) === 1) {
            $methods[] = $title;
        }
    }

    return sortedUnique($methods);
}

/**
 * @return list<string>
 */
function officialUpdateTypes(string $html): array
{
    if (! preg_match('/<h4><a class="anchor" name="update".*?<table class="table">(.*?)<\/table>/s', $html, $match)) {
        fwrite(STDERR, "Failed to find the Telegram Update fields table.\n");
        exit(1);
    }

    preg_match_all('/<td>([a-z_]+)<\/td>/', $match[1], $fieldMatches);

    return sortedUnique(array_values(array_filter(
        $fieldMatches[1],
        static fn (string $field): bool => $field !== 'update_id',
    )));
}

/**
 * @return list<string>
 */
function localUpdateTypes(): array
{
    $reflection = new ReflectionClass(TelegramWebhookUpdate::class);
    $constant = $reflection->getReflectionConstant('UPDATE_TYPES');

    if ($constant === false) {
        fwrite(STDERR, "Failed to read local Telegram webhook update types.\n");
        exit(1);
    }

    /** @var list<string> $updateTypes */
    $updateTypes = $constant->getValue();

    return sortedUnique($updateTypes);
}

/**
 * @param  list<string>  $official
 * @param  list<string>  $local
 * @return list<string>
 */
function diffLists(string $label, array $official, array $local): array
{
    $missing = array_values(array_diff($official, $local));
    $extra = array_values(array_diff($local, $official));
    $failures = [];

    if ($missing !== []) {
        $failures[] = sprintf('Missing official Telegram Bot API %ss: %s.', $label, implode(', ', $missing));
    }

    if ($extra !== []) {
        $failures[] = sprintf('Local unknown Telegram Bot API %ss: %s.', $label, implode(', ', $extra));
    }

    return $failures;
}

/**
 * @param  list<string>  $values
 * @return list<string>
 */
function sortedUnique(array $values): array
{
    $values = array_values(array_unique($values));
    sort($values);

    return $values;
}
