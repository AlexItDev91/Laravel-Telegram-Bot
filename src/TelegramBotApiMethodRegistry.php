<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

final class TelegramBotApiMethodRegistry
{
    public const string BOT_API_VERSION = '10.1';

    public const string BOT_API_RELEASE_DATE = '2026-06-11';

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
