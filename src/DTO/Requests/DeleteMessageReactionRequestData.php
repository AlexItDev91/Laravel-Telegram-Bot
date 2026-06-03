<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteMessageReaction`.
 */
final readonly class DeleteMessageReactionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'deleteMessageReaction';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageId,
        ?int $userId = null,
        ?int $actorChatId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'user_id' => $userId,
            'actor_chat_id' => $actorChatId,
        ], $extra)));
    }
}
