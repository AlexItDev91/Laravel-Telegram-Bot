<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendLocation`.
 */
final readonly class SendLocationRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'sendLocation';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        float $latitude,
        float $longitude,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?float $horizontalAccuracy = null,
        ?int $livePeriod = null,
        ?int $heading = null,
        ?int $proximityAlertRadius = null,
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
            'latitude' => $latitude,
            'longitude' => $longitude,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'horizontal_accuracy' => $horizontalAccuracy,
            'live_period' => $livePeriod,
            'heading' => $heading,
            'proximity_alert_radius' => $proximityAlertRadius,
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
