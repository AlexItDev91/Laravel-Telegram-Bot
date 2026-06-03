<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setStickerSetTitle`.
 */
final readonly class SetStickerSetTitleRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setStickerSetTitle';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $name,
        string $title,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'name' => $name,
            'title' => $title,
        ], $extra)));
    }
}
