<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getBusinessAccountGifts`.
 */
final readonly class GetBusinessAccountGiftsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getBusinessAccountGifts';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        ?bool $excludeUnsaved = null,
        ?bool $excludeSaved = null,
        ?bool $excludeUnlimited = null,
        ?bool $excludeLimitedUpgradable = null,
        ?bool $excludeLimitedNonUpgradable = null,
        ?bool $excludeUnique = null,
        ?bool $excludeFromBlockchain = null,
        ?bool $sortByPrice = null,
        ?string $offset = null,
        ?int $limit = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'exclude_unsaved' => $excludeUnsaved,
            'exclude_saved' => $excludeSaved,
            'exclude_unlimited' => $excludeUnlimited,
            'exclude_limited_upgradable' => $excludeLimitedUpgradable,
            'exclude_limited_non_upgradable' => $excludeLimitedNonUpgradable,
            'exclude_unique' => $excludeUnique,
            'exclude_from_blockchain' => $excludeFromBlockchain,
            'sort_by_price' => $sortByPrice,
            'offset' => $offset,
            'limit' => $limit,
        ], $extra)));
    }
}
