<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `transferBusinessAccountStars`.
 */
final readonly class TransferBusinessAccountStarsRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'transferBusinessAccountStars';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int $starCount,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'star_count' => $starCount,
        ], $extra)));
    }
}
