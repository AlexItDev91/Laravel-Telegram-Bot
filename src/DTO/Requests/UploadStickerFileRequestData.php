<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\InputFile;

/**
 * Generated typed request builder for Telegram Bot API method `uploadStickerFile`.
 */
final readonly class UploadStickerFileRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'uploadStickerFile';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        InputFile $sticker,
        string $stickerFormat,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'sticker' => $sticker,
            'sticker_format' => $stickerFormat,
        ], $extra)));
    }
}
