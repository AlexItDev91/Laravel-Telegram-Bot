<?php

namespace Aptenova\TelegramBot;

use Aptenova\TelegramBot\Enums\TelegramBotApiMethod;

final class TelegramBotApiMethodRegistry
{
    public const BOT_API_VERSION = '10.0';

    public const BOT_API_RELEASE_DATE = '2026-05-08';

    public static function supports(string $method): bool
    {
        return TelegramBotApiMethod::tryFrom($method) !== null;
    }

    /**
     * @return list<string>
     */
    public static function methods(): array
    {
        return array_map(
            static fn (TelegramBotApiMethod $method): string => $method->value,
            TelegramBotApiMethod::cases(),
        );
    }
}
