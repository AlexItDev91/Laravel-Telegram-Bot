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
$officialMethodParameters = officialMethodParameters($html, $officialMethods);
$documentedMethodParameters = documentedMethodParameters(__DIR__.'/../docs/METHODS.md', $localMethods);
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

foreach (diffMethodParameters($officialMethodParameters, $documentedMethodParameters) as $failure) {
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
    "Telegram Bot API surface is current: version %s released on %s, %d methods, %d documented parameters, %d update types.\n",
    $officialVersion,
    $officialReleaseDate,
    count($localMethods),
    countMethodParameters($documentedMethodParameters),
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
 * @param  list<string>  $methods
 * @return array<string, list<array{name: string, type: string, required: string}>>
 */
function officialMethodParameters(string $html, array $methods): array
{
    preg_match_all(
        '/<h4><a class="anchor" name="([^"]+)"[^>]*>.*?<\/a>(.*?)<\/h4>(.*?)(?=<h4><a class="anchor" name="[^"]+"|$)/s',
        $html,
        $matches,
        PREG_SET_ORDER,
    );

    $methodSet = array_fill_keys($methods, true);
    $parameters = [];

    foreach ($matches as $match) {
        $method = trim(strip_tags($match[2]));

        if (! isset($methodSet[$method])) {
            continue;
        }

        $parameters[$method] = officialParametersFromBlock($match[3]);
    }

    foreach ($methods as $method) {
        if (! array_key_exists($method, $parameters)) {
            fwrite(STDERR, "Failed to find official Telegram Bot API method section [{$method}].\n");
            exit(1);
        }
    }

    ksort($parameters);

    return $parameters;
}

/**
 * @return list<array{name: string, type: string, required: string}>
 */
function officialParametersFromBlock(string $html): array
{
    if (! preg_match('/<table class="table">\s*<thead>.*?<th>Parameter<\/th>.*?<th>Type<\/th>.*?<th>Required<\/th>.*?<\/thead>\s*<tbody>(.*?)<\/tbody>\s*<\/table>/s', $html, $table)) {
        return [];
    }

    preg_match_all('/<tr>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>/s', $table[1], $rows, PREG_SET_ORDER);

    return array_map(
        static fn (array $row): array => [
            'name' => htmlCellText($row[1]),
            'type' => htmlCellText($row[2]),
            'required' => htmlCellText($row[3]),
        ],
        $rows,
    );
}

/**
 * @param  list<string>  $methods
 * @return array<string, list<array{name: string, type: string, required: string}>>
 */
function documentedMethodParameters(string $path, array $methods): array
{
    $markdown = file_get_contents($path);

    if ($markdown === false) {
        fwrite(STDERR, "Failed to read local method documentation [{$path}].\n");
        exit(1);
    }

    $parameters = [];

    foreach ($methods as $method) {
        if (! preg_match('/^### `'.preg_quote($method, '/').'`\R(.*?)(?=^### `|\z)/ms', $markdown, $section)) {
            fwrite(STDERR, "Failed to find documented Telegram Bot API method section [{$method}].\n");
            exit(1);
        }

        $parameters[$method] = documentedParametersFromSection($section[1], $method);
    }

    ksort($parameters);

    return $parameters;
}

/**
 * @return list<array{name: string, type: string, required: string}>
 */
function documentedParametersFromSection(string $markdown, string $method): array
{
    preg_match_all('/^\| `([^`]+)` \| `([^`]+)` \| `(Yes|Optional)` \|$/m', $markdown, $rows, PREG_SET_ORDER);

    if ($rows === []) {
        if (str_contains($markdown, 'Parameters: none.')) {
            return [];
        }

        fwrite(STDERR, "Failed to find documented parameter table for method [{$method}].\n");
        exit(1);
    }

    return array_map(
        static fn (array $row): array => [
            'name' => $row[1],
            'type' => normalizeWhitespace($row[2]),
            'required' => $row[3],
        ],
        $rows,
    );
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
 * @param  array<string, list<array{name: string, type: string, required: string}>>  $official
 * @param  array<string, list<array{name: string, type: string, required: string}>>  $documented
 * @return list<string>
 */
function diffMethodParameters(array $official, array $documented): array
{
    $failures = [];

    foreach ($official as $method => $officialParameters) {
        if (! array_key_exists($method, $documented)) {
            $failures[] = "Missing documented Telegram Bot API parameter matrix for method [{$method}].";

            continue;
        }

        $officialByName = parameterMap($officialParameters);
        $documentedByName = parameterMap($documented[$method]);
        $missing = array_values(array_diff(array_keys($officialByName), array_keys($documentedByName)));
        $extra = array_values(array_diff(array_keys($documentedByName), array_keys($officialByName)));

        if ($missing !== []) {
            $failures[] = sprintf('Missing documented parameters for [%s]: %s.', $method, implode(', ', $missing));
        }

        if ($extra !== []) {
            $failures[] = sprintf('Local unknown documented parameters for [%s]: %s.', $method, implode(', ', $extra));
        }

        foreach (array_intersect(array_keys($officialByName), array_keys($documentedByName)) as $parameter) {
            $officialParameter = $officialByName[$parameter];
            $documentedParameter = $documentedByName[$parameter];

            if ($officialParameter['type'] !== $documentedParameter['type'] || $officialParameter['required'] !== $documentedParameter['required']) {
                $failures[] = sprintf(
                    'Documented parameter mismatch for [%s.%s]: official [%s, %s], local [%s, %s].',
                    $method,
                    $parameter,
                    $officialParameter['type'],
                    $officialParameter['required'],
                    $documentedParameter['type'],
                    $documentedParameter['required'],
                );
            }
        }
    }

    return $failures;
}

/**
 * @param  list<array{name: string, type: string, required: string}>  $parameters
 * @return array<string, array{name: string, type: string, required: string}>
 */
function parameterMap(array $parameters): array
{
    $mapped = [];

    foreach ($parameters as $parameter) {
        $mapped[$parameter['name']] = $parameter;
    }

    return $mapped;
}

/**
 * @param  array<string, list<array{name: string, type: string, required: string}>>  $methods
 */
function countMethodParameters(array $methods): int
{
    return array_sum(array_map('count', $methods));
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

function htmlCellText(string $html): string
{
    return normalizeWhitespace(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5));
}

function normalizeWhitespace(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', $value));
}
