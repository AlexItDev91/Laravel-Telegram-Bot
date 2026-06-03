<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `approveSuggestedPost`.
 */
final readonly class ApproveSuggestedPostRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'approveSuggestedPost';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $chatId,
        int $messageId,
        ?int $sendDate = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'send_date' => $sendDate,
        ], $extra)));
    }
}
