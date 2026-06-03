<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setStickerMaskPosition`.
 */
final readonly class SetStickerMaskPositionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setStickerMaskPosition';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $maskPosition
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $sticker,
        TelegramBotData|array|null $maskPosition = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'sticker' => $sticker,
            'mask_position' => $maskPosition,
        ], $extra)));
    }
}
