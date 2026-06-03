<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editMessageChecklist`.
 */
final readonly class EditMessageChecklistRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editMessageChecklist';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $checklist
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int|string $chatId,
        int $messageId,
        TelegramBotData|array $checklist,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'checklist' => $checklist,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
