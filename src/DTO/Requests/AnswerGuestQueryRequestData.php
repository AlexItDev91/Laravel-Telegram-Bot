<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `answerGuestQuery`.
 */
final readonly class AnswerGuestQueryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'answerGuestQuery';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $result
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $guestQueryId,
        TelegramBotData|array $result,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'guest_query_id' => $guestQueryId,
            'result' => $result,
        ], $extra)));
    }
}
