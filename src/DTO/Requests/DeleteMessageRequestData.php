<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteMessage`.
 */
final readonly class DeleteMessageRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'deleteMessage';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ], $extra)));
    }
}
