<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getBusinessConnection`.
 */
final readonly class GetBusinessConnectionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getBusinessConnection';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
        ], $extra)));
    }
}
