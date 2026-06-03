<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setChatStickerSet`.
 */
final readonly class SetChatStickerSetRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setChatStickerSet';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $stickerSetName,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'sticker_set_name' => $stickerSetName,
        ], $extra)));
    }
}
