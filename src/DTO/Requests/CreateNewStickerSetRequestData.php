<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `createNewStickerSet`.
 */
final readonly class CreateNewStickerSetRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'createNewStickerSet';

    /**
     * @param  array<string|int, mixed>  $stickers
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        string $name,
        string $title,
        array $stickers,
        ?string $stickerType = null,
        ?bool $needsRepainting = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'name' => $name,
            'title' => $title,
            'stickers' => $stickers,
            'sticker_type' => $stickerType,
            'needs_repainting' => $needsRepainting,
        ], $extra)));
    }
}
