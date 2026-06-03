<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `stopPoll`.
 */
final readonly class StopPollRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'stopPoll';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageId,
        ?string $businessConnectionId = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'business_connection_id' => $businessConnectionId,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
