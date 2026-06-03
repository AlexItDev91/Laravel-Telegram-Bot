<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setMyDefaultAdministratorRights`.
 */
final readonly class SetMyDefaultAdministratorRightsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setMyDefaultAdministratorRights';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $rights
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        TelegramBotData|array|null $rights = null,
        ?bool $forChannels = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'rights' => $rights,
            'for_channels' => $forChannels,
        ], $extra)));
    }
}
