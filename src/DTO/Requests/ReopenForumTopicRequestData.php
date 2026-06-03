<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `reopenForumTopic`.
 */
final readonly class ReopenForumTopicRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'reopenForumTopic';

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
