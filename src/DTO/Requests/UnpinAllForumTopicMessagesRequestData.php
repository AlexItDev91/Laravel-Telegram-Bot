<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `unpinAllForumTopicMessages`.
 */
final readonly class UnpinAllForumTopicMessagesRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'unpinAllForumTopicMessages';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageThreadId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_thread_id' => $messageThreadId,
        ], $extra)));
    }
}
