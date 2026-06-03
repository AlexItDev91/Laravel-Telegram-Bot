<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\InputFile;

/**
 * Generated typed request builder for Telegram Bot API method `setStickerSetThumbnail`.
 */
final readonly class SetStickerSetThumbnailRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setStickerSetThumbnail';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $name,
        int $userId,
        string $format,
        InputFile|string|null $thumbnail = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'name' => $name,
            'user_id' => $userId,
            'format' => $format,
            'thumbnail' => $thumbnail,
        ], $extra)));
    }
}
