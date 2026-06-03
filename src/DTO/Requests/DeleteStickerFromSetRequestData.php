<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteStickerFromSet`.
 */
final readonly class DeleteStickerFromSetRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'deleteStickerFromSet';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $sticker,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'sticker' => $sticker,
        ], $extra)));
    }
}
