<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteMessages`.
 */
final readonly class DeleteMessagesRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'deleteMessages';

    /**
     * @param  array<string|int, mixed>  $messageIds
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        array $messageIds,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_ids' => $messageIds,
        ], $extra)));
    }
}
