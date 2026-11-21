<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getUniqueGift`.
 */
final readonly class GetUniqueGiftRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getUniqueGift';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $name,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'name' => $name,
        ], $extra)));
    }
}
