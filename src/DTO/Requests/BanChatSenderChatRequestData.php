<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `banChatSenderChat`.
 */
final readonly class BanChatSenderChatRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'banChatSenderChat';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $senderChatId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'sender_chat_id' => $senderChatId,
        ], $extra)));
    }
}
