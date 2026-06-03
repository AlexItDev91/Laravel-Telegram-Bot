<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `answerInlineQuery`.
 */
final readonly class AnswerInlineQueryRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'answerInlineQuery';

    /**
     * @param  array<string|int, mixed>  $results
     * @param  TelegramBotData|array<string|int, mixed>|null  $button
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $inlineQueryId,
        array $results,
        ?int $cacheTime = null,
        ?bool $isPersonal = null,
        ?string $nextOffset = null,
        TelegramBotData|array|null $button = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'inline_query_id' => $inlineQueryId,
            'results' => $results,
            'cache_time' => $cacheTime,
            'is_personal' => $isPersonal,
            'next_offset' => $nextOffset,
            'button' => $button,
        ], $extra)));
    }
}
