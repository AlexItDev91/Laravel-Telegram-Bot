<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteAllMessageReactions`.
 */
final readonly class DeleteAllMessageReactionsRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'deleteAllMessageReactions';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?int $userId = null,
        ?int $actorChatId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'actor_chat_id' => $actorChatId,
        ], $extra)));
    }
}
