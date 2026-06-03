<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getStickerSet`.
 */
final readonly class GetStickerSetRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getStickerSet';

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
