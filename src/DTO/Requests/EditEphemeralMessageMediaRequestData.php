<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editEphemeralMessageMedia`.
 */
final readonly class EditEphemeralMessageMediaRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editEphemeralMessageMedia';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $media
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        TelegramBotData|array $media,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'receiver_user_id' => $receiverUserId,
            'ephemeral_message_id' => $ephemeralMessageId,
            'media' => $media,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
