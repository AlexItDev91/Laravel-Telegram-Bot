<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `upgradeGift`.
 */
final readonly class UpgradeGiftRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'upgradeGift';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        string $ownedGiftId,
        ?bool $keepOriginalDetails = null,
        ?int $starCount = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'owned_gift_id' => $ownedGiftId,
            'keep_original_details' => $keepOriginalDetails,
            'star_count' => $starCount,
        ], $extra)));
    }
}
