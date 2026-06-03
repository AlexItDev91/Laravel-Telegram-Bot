<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteStickerSet`.
 */
final readonly class DeleteStickerSetRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'deleteStickerSet';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $name,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'name' => $name,
        ], $extra)));
    }
}
