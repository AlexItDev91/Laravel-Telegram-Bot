<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setMyProfilePhoto`.
 */
final readonly class SetMyProfilePhotoRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setMyProfilePhoto';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $photo
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        TelegramBotData|array $photo,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'photo' => $photo,
        ], $extra)));
    }
}
