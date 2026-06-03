<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `copyMessages`.
 */
final readonly class CopyMessagesRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'copyMessages';

    /**
     * @param  array<string|int, mixed>  $messageIds
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int|string $fromChatId,
        array $messageIds,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $removeCaption = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'message_ids' => $messageIds,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'remove_caption' => $removeCaption,
        ], $extra)));
    }
}
