<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `getMyCommands`.
 */
final readonly class GetMyCommandsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getMyCommands';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $scope
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        TelegramBotData|array|null $scope = null,
        ?string $languageCode = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'scope' => $scope,
            'language_code' => $languageCode,
        ], $extra)));
    }
}
