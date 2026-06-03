<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editMessageLiveLocation`.
 */
final readonly class EditMessageLiveLocationRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editMessageLiveLocation';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        float $latitude,
        float $longitude,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?int $livePeriod = null,
        ?float $horizontalAccuracy = null,
        ?int $heading = null,
        ?int $proximityAlertRadius = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
            'live_period' => $livePeriod,
            'horizontal_accuracy' => $horizontalAccuracy,
            'heading' => $heading,
            'proximity_alert_radius' => $proximityAlertRadius,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
