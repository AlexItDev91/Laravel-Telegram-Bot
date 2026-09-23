<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editEphemeralMessageText`.
 */
final readonly class EditEphemeralMessageTextRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editEphemeralMessageText';

    /**
     * @param  array<string|int, mixed>|null  $entities
     * @param  TelegramBotData|array<string|int, mixed>|null  $richMessage
     * @param  TelegramBotData|array<string|int, mixed>|null  $linkPreviewOptions
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        ?string $text = null,
        string|TelegramParseMode|null $parseMode = null,
        ?array $entities = null,
        TelegramBotData|array|null $richMessage = null,
        TelegramBotData|array|null $linkPreviewOptions = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'receiver_user_id' => $receiverUserId,
            'ephemeral_message_id' => $ephemeralMessageId,
            'text' => $text,
            'parse_mode' => $parseMode,
            'entities' => $entities,
            'rich_message' => $richMessage,
            'link_preview_options' => $linkPreviewOptions,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
