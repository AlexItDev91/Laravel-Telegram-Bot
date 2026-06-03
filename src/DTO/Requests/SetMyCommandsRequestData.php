<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setMyCommands`.
 */
final readonly class SetMyCommandsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setMyCommands';

    /**
     * @param  array<string|int, mixed>  $commands
     * @param  TelegramBotData|array<string|int, mixed>|null  $scope
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $commands,
        TelegramBotData|array|null $scope = null,
        ?string $languageCode = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'commands' => $commands,
            'scope' => $scope,
            'language_code' => $languageCode,
        ], $extra)));
    }
}
