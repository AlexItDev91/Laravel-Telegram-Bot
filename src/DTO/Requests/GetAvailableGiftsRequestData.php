<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getAvailableGifts`.
 */
final readonly class GetAvailableGiftsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getAvailableGifts';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
