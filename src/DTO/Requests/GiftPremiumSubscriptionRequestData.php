<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

/**
 * Generated typed request builder for Telegram Bot API method `giftPremiumSubscription`.
 */
final readonly class GiftPremiumSubscriptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'giftPremiumSubscription';

    /**
     * @param  array<string|int, mixed>|null  $textEntities
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        int $monthCount,
        int $starCount,
        ?string $text = null,
        string|TelegramParseMode|null $textParseMode = null,
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
