<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteBusinessMessages`.
 */
final readonly class DeleteBusinessMessagesRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'deleteBusinessMessages';

    /**
     * @param  array<string|int, mixed>  $messageIds
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        array $messageIds,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'message_ids' => $messageIds,
        ], $extra)));
    }
}
