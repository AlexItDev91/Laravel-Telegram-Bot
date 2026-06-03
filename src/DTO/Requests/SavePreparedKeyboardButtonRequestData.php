<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `savePreparedKeyboardButton`.
 */
final readonly class SavePreparedKeyboardButtonRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'savePreparedKeyboardButton';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $button
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        TelegramBotData|array $button,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'button' => $button,
        ], $extra)));
    }
}
