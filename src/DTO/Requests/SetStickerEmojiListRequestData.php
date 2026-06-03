<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setStickerEmojiList`.
 */
final readonly class SetStickerEmojiListRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setStickerEmojiList';

    /**
     * @param  array<string|int, mixed>  $emojiList
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $sticker,
        array $emojiList,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'sticker' => $sticker,
            'emoji_list' => $emojiList,
        ], $extra)));
    }
}
