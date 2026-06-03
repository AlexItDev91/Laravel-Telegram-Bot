<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setChatTitle`.
 */
final readonly class SetChatTitleRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setChatTitle';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $title,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'title' => $title,
        ], $extra)));
    }
}
