<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteEphemeralMessage`.
 */
final readonly class DeleteEphemeralMessageRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'deleteEphemeralMessage';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'receiver_user_id' => $receiverUserId,
            'ephemeral_message_id' => $ephemeralMessageId,
        ], $extra)));
    }
}
