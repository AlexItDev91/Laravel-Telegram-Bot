#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
$methodsPath = $root.'/docs/METHODS.md';
$targetPath = $root.'/src/TelegramBotApiMethodSchema.php';

$markdown = file_get_contents($methodsPath);

if ($markdown === false) {
    fwrite(STDERR, "Failed to read $methodsPath.\n");
    exit(1);
}

$schema = [];

preg_match_all('/^### `([^`]+)`\R(.*?)(?=^### `|\z)/ms', $markdown, $sections, PREG_SET_ORDER);

foreach ($sections as $section) {
    [, $method, $body] = $section;

    preg_match_all('/^\| `([^`]+)` \| `([^`]+)` \| `(Yes|Optional)` \|$/m', $body, $rows, PREG_SET_ORDER);

    $schema[$method] = array_map(
        static fn (array $row): array => [
            'name' => $row[1],
            'type' => preg_replace('/\s+/', ' ', $row[2]) ?? $row[2],
            'required' => $row[3] === 'Yes',
        ],
        $rows,
    );
}

ksort($schema);

$export = shortArrayExport($schema, 2);
$content = <<<PHP
<?php

namespace AlexItDev91\\LaravelTelegramBot;

use AlexItDev91\\LaravelTelegramBot\\Enums\\TelegramBotApiMethod;

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiMethodSchema
{
    /**
     * @var array<string, list<array{name: string, type: string, required: bool}>>
     */
    private const PARAMETERS = $export;

    public static function supports(string|TelegramBotApiMethod \$method): bool
    {
        return array_key_exists(self::methodName(\$method), self::PARAMETERS);
    }

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    public static function parameters(string|TelegramBotApiMethod \$method): array
    {
        return self::PARAMETERS[self::methodName(\$method)] ?? [];
    }

    /**
     * @return list<string>
     */
    public static function requiredParameters(string|TelegramBotApiMethod \$method): array
    {
        return array_values(array_map(
            static fn (array \$parameter): string => \$parameter['name'],
            array_filter(self::parameters(\$method), static fn (array \$parameter): bool => \$parameter['required']),
        ));
    }

    /**
     * @return array<string, list<array{name: string, type: string, required: bool}>>
     */
    public static function all(): array
    {
        return self::PARAMETERS;
    }

    private static function methodName(string|TelegramBotApiMethod \$method): string
    {
        return \$method instanceof TelegramBotApiMethod ? \$method->value : \$method;
    }
}

PHP;

if (file_put_contents($targetPath, $content) === false) {
    fwrite(STDERR, "Failed to write $targetPath.\n");
    exit(1);
}

printf("Generated %s with %d methods and %d parameters.\n", $targetPath, count($schema), array_sum(array_map('count', $schema)));

/**
 * @param  array<string|int, mixed>  $value
 */
function shortArrayExport(array $value, int $indent = 1): string
{
    $spaces = str_repeat('    ', $indent);
    $outer = str_repeat('    ', $indent - 1);
    $isList = array_is_list($value);
    $lines = ['['];

    foreach ($value as $key => $item) {
        $prefix = $isList ? $spaces : $spaces.var_export($key, true).' => ';

        if (is_array($item)) {
            $lines[] = $prefix.shortArrayExport($item, $indent + 1).',';

            continue;
        }

        $lines[] = $prefix.var_export($item, true).',';
    }

    $lines[] = $outer.']';

    return implode("\n", $lines);
}
