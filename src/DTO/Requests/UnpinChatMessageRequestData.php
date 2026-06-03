<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `unpinChatMessage`.
 */
final readonly class UnpinChatMessageRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'unpinChatMessage';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?string $businessConnectionId = null,
        ?int $messageId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'business_connection_id' => $businessConnectionId,
            'message_id' => $messageId,
        ], $extra)));
    }
}
