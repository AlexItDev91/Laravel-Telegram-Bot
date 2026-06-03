<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editMessageText`.
 */
final readonly class EditMessageTextRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editMessageText';

    /**
     * @param  array<string|int, mixed>|null  $entities
     * @param  TelegramBotData|array<string|int, mixed>|null  $linkPreviewOptions
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $text,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $parseMode = null,
        ?array $entities = null,
        TelegramBotData|array|null $linkPreviewOptions = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'text' => $text,
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
            'parse_mode' => $parseMode,
            'entities' => $entities,
            'link_preview_options' => $linkPreviewOptions,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
