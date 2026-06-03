<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendMediaGroup`.
 */
final readonly class SendMediaGroupRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'sendMediaGroup';

    /**
     * @param  array<string|int, mixed>  $media
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        array $media,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $replyParameters = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'media' => $media,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'message_effect_id' => $messageEffectId,
            'reply_parameters' => $replyParameters,
        ], $extra)));
    }
}
