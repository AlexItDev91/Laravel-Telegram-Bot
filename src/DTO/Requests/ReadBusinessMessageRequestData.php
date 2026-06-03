<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `readBusinessMessage`.
 */
final readonly class ReadBusinessMessageRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'readBusinessMessage';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int $chatId,
        int $messageId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ], $extra)));
    }
}
