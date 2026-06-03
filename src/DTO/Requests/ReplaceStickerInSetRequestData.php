<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `replaceStickerInSet`.
 */
final readonly class ReplaceStickerInSetRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'replaceStickerInSet';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $sticker
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        string $name,
        string $oldSticker,
        TelegramBotData|array $sticker,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'name' => $name,
            'old_sticker' => $oldSticker,
            'sticker' => $sticker,
        ], $extra)));
    }
}
