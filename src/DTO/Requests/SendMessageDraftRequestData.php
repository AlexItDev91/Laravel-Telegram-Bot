<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `sendMessageDraft`.
 */
final readonly class SendMessageDraftRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendMessageDraft';

    /**
     * @param  array<string|int, mixed>|null  $entities
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $chatId,
        int $draftId,
        ?int $messageThreadId = null,
        ?string $text = null,
        ?string $parseMode = null,
        ?array $entities = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'draft_id' => $draftId,
            'message_thread_id' => $messageThreadId,
            'text' => $text,
            'parse_mode' => $parseMode,
            'entities' => $entities,
        ], $extra)));
    }
}
