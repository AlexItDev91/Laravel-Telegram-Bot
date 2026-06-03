<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getChatMenuButton`.
 */
final readonly class GetChatMenuButtonRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getChatMenuButton';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?int $chatId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
        ], $extra)));
    }
}
