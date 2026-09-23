<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editEphemeralMessageCaption`.
 */
final readonly class EditEphemeralMessageCaptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editEphemeralMessageCaption';

    /**
     * @param  array<string|int, mixed>|null  $captionEntities
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        ?string $caption = null,
        string|TelegramParseMode|null $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'receiver_user_id' => $receiverUserId,
            'ephemeral_message_id' => $ephemeralMessageId,
            'caption' => $caption,
            'parse_mode' => $parseMode,
            'caption_entities' => $captionEntities,
            'show_caption_above_media' => $showCaptionAboveMedia,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
