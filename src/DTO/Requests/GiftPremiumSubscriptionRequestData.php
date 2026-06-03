<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `giftPremiumSubscription`.
 */
final readonly class GiftPremiumSubscriptionRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'giftPremiumSubscription';

    /**
     * @param  array<string|int, mixed>|null  $textEntities
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        int $monthCount,
        int $starCount,
        ?string $text = null,
        ?string $textParseMode = null,
        ?array $textEntities = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'month_count' => $monthCount,
            'star_count' => $starCount,
            'text' => $text,
            'text_parse_mode' => $textParseMode,
            'text_entities' => $textEntities,
        ], $extra)));
    }
}
