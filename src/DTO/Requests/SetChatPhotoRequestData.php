<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\InputFile;

/**
 * Generated typed request builder for Telegram Bot API method `setChatPhoto`.
 */
final readonly class SetChatPhotoRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setChatPhoto';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        InputFile $photo,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'photo' => $photo,
        ], $extra)));
    }
}
