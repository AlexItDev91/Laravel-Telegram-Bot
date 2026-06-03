<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `declineSuggestedPost`.
 */
final readonly class DeclineSuggestedPostRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'declineSuggestedPost';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $chatId,
        int $messageId,
        ?string $comment = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'comment' => $comment,
        ], $extra)));
    }
}
