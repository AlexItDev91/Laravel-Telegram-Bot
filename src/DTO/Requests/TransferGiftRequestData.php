<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `transferGift`.
 */
final readonly class TransferGiftRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'transferGift';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        string $ownedGiftId,
        int $newOwnerChatId,
        ?int $starCount = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'owned_gift_id' => $ownedGiftId,
            'new_owner_chat_id' => $newOwnerChatId,
            'star_count' => $starCount,
        ], $extra)));
    }
}
