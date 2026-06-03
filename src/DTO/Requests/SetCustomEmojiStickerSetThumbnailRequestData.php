<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setCustomEmojiStickerSetThumbnail`.
 */
final readonly class SetCustomEmojiStickerSetThumbnailRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setCustomEmojiStickerSetThumbnail';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $name,
        ?string $customEmojiId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'name' => $name,
            'custom_emoji_id' => $customEmojiId,
        ], $extra)));
    }
}
