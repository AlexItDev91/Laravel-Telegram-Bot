<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendRichMessageDraft`.
 */
final readonly class SendRichMessageDraftRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendRichMessageDraft';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $richMessage
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $chatId,
        int $draftId,
        TelegramBotData|array $richMessage,
        ?int $messageThreadId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'draft_id' => $draftId,
            'rich_message' => $richMessage,
            'message_thread_id' => $messageThreadId,
        ], $extra)));
    }
}
