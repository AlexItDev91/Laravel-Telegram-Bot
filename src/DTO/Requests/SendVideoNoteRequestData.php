<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendVideoNote`.
 */
final readonly class SendVideoNoteRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendVideoNote';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        InputFile|string $videoNote,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?int $duration = null,
        ?int $length = null,
        InputFile|string|null $thumbnail = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $suggestedPostParameters = null,
        TelegramBotData|array|null $replyParameters = null,
        mixed $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'video_note' => $videoNote,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'duration' => $duration,
            'length' => $length,
            'thumbnail' => $thumbnail,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'message_effect_id' => $messageEffectId,
            'suggested_post_parameters' => $suggestedPostParameters,
            'reply_parameters' => $replyParameters,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
