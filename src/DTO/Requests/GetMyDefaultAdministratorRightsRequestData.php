<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getMyDefaultAdministratorRights`.
 */
final readonly class GetMyDefaultAdministratorRightsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getMyDefaultAdministratorRights';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?bool $forChannels = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'for_channels' => $forChannels,
        ], $extra)));
    }
}
