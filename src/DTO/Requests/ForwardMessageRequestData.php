<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `forwardMessage`.
 */
final readonly class ForwardMessageRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'forwardMessage';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $suggestedPostParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int|string $fromChatId,
        int $messageId,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?int $videoStartTimestamp = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $suggestedPostParameters = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'video_start_timestamp' => $videoStartTimestamp,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'message_effect_id' => $messageEffectId,
            'suggested_post_parameters' => $suggestedPostParameters,
        ], $extra)));
    }
}
