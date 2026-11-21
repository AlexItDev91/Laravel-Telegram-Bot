<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\InputFile;

final readonly class SendPhotoData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, array<string, mixed>>|null  $captionEntities
     * @param  TelegramBotData|array<string, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     * @param  TelegramBotData|array<string, mixed>|null  $ephemeralMessageParameters
     */
    public function __construct(
        int|string|null $chatId = null,
        string|InputFile|null $photo = null,
        ?string $businessConnectionId = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
        ?string $caption = null,
        string|TelegramParseMode|null $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $hasSpoiler = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $suggestedPostParameters = null,
        TelegramBotData|array|null $replyParameters = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
        TelegramBotData|array|null $ephemeralMessageParameters = null,
    ) {
        parent::__construct(self::messagePayload([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'photo' => $photo,
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
        ], $extra, ['photo'], $ephemeralMessageParameters));
    }
}
