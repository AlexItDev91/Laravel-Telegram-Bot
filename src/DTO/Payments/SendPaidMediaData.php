<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

final readonly class SendPaidMediaData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, TelegramBotData|array<string, mixed>>  $media
     * @param  array<int, array<string, mixed>>|null  $captionEntities
     * @param  TelegramBotData|array<string, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $chatId,
        int $starCount,
        array $media,
        ?string $businessConnectionId = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
        ?string $payload = null,
        ?string $caption = null,
        string|TelegramParseMode|null $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        TelegramBotData|array|null $suggestedPostParameters = null,
        TelegramBotData|array|null $replyParameters = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ) {
        self::assertPositiveInteger('star_count', $starCount);

        parent::__construct(self::payload([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'star_count' => $starCount,
            'media' => $media,
            'payload' => $payload,
            'caption' => $caption,
            'parse_mode' => $parseMode,
            'caption_entities' => $captionEntities,
            'show_caption_above_media' => $showCaptionAboveMedia,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'suggested_post_parameters' => $suggestedPostParameters,
            'reply_parameters' => $replyParameters,
            'reply_markup' => $replyMarkup,
        ], $extra, ['chat_id', 'media']));
    }
}
