<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setStickerPositionInSet`.
 */
final readonly class SetStickerPositionInSetRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setStickerPositionInSet';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $sticker,
        int $position,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'sticker' => $sticker,
            'position' => $position,
        ], $extra)));
    }
}
