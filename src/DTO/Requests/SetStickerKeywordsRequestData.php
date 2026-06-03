<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setStickerKeywords`.
 */
final readonly class SetStickerKeywordsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setStickerKeywords';

    /**
     * @param  array<string|int, mixed>|null  $keywords
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $sticker,
        ?array $keywords = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'sticker' => $sticker,
            'keywords' => $keywords,
        ], $extra)));
    }
}
