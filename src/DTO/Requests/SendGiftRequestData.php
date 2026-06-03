<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `sendGift`.
 */
final readonly class SendGiftRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'sendGift';

    /**
     * @param  array<string|int, mixed>|null  $textEntities
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $giftId,
        ?int $userId = null,
        int|string|null $chatId = null,
        ?bool $payForUpgrade = null,
        ?string $text = null,
        ?string $textParseMode = null,
        ?array $textEntities = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'gift_id' => $giftId,
            'user_id' => $userId,
            'chat_id' => $chatId,
            'pay_for_upgrade' => $payForUpgrade,
            'text' => $text,
            'text_parse_mode' => $textParseMode,
            'text_entities' => $textEntities,
        ], $extra)));
    }
}
