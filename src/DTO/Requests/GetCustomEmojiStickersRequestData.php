<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getCustomEmojiStickers`.
 */
final readonly class GetCustomEmojiStickersRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getCustomEmojiStickers';

    /**
     * @param  array<string|int, mixed>  $customEmojiIds
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $customEmojiIds,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'custom_emoji_ids' => $customEmojiIds,
        ], $extra)));
    }
}
