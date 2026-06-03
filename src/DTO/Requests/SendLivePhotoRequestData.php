<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendLivePhoto`.
 */
final readonly class SendLivePhotoRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'sendLivePhoto';

    /**
     * @param  array<string|int, mixed>|null  $captionEntities
     * @param  TelegramBotData|array<string|int, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        InputFile|string $livePhoto,
        InputFile|string $photo,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $hasSpoiler = null,
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
            'live_photo' => $livePhoto,
            'photo' => $photo,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'caption' => $caption,
            'parse_mode' => $parseMode,
            'caption_entities' => $captionEntities,
            'show_caption_above_media' => $showCaptionAboveMedia,
            'has_spoiler' => $hasSpoiler,
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
