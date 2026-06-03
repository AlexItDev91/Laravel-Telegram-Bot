<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `answerWebAppQuery`.
 */
final readonly class AnswerWebAppQueryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'answerWebAppQuery';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $result
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $webAppQueryId,
        TelegramBotData|array $result,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'web_app_query_id' => $webAppQueryId,
            'result' => $result,
        ], $extra)));
    }
}
