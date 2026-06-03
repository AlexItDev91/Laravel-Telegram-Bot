<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getChatGifts`.
 */
final readonly class GetChatGiftsRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getChatGifts';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?bool $excludeUnsaved = null,
        ?bool $excludeSaved = null,
        ?bool $excludeUnlimited = null,
        ?bool $excludeLimitedUpgradable = null,
        ?bool $excludeLimitedNonUpgradable = null,
        ?bool $excludeFromBlockchain = null,
        ?bool $excludeUnique = null,
        ?bool $sortByPrice = null,
        ?string $offset = null,
        ?int $limit = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'exclude_unsaved' => $excludeUnsaved,
            'exclude_saved' => $excludeSaved,
            'exclude_unlimited' => $excludeUnlimited,
            'exclude_limited_upgradable' => $excludeLimitedUpgradable,
            'exclude_limited_non_upgradable' => $excludeLimitedNonUpgradable,
            'exclude_from_blockchain' => $excludeFromBlockchain,
            'exclude_unique' => $excludeUnique,
            'sort_by_price' => $sortByPrice,
            'offset' => $offset,
            'limit' => $limit,
        ], $extra)));
    }
}
