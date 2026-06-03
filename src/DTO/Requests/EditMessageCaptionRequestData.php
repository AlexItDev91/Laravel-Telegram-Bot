<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editMessageCaption`.
 */
final readonly class EditMessageCaptionRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editMessageCaption';

    /**
     * @param  array<string|int, mixed>|null  $captionEntities
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
            'caption' => $caption,
            'parse_mode' => $parseMode,
            'caption_entities' => $captionEntities,
            'show_caption_above_media' => $showCaptionAboveMedia,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
