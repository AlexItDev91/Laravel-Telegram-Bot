<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `convertGiftToStars`.
 */
final readonly class ConvertGiftToStarsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'convertGiftToStars';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        string $ownedGiftId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'owned_gift_id' => $ownedGiftId,
        ], $extra)));
    }
}
