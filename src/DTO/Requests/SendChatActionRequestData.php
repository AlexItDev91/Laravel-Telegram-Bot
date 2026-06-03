<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `sendChatAction`.
 */
final readonly class SendChatActionRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'sendChatAction';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $action,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'action' => $action,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
        ], $extra)));
    }
}
