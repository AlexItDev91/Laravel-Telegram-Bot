<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `answerWidgetQuery`.
 */
final readonly class AnswerWidgetQueryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'answerWidgetQuery';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $widgetQueryId,
        ?string $data = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'widget_query_id' => $widgetQueryId,
            'data' => $data,
        ], $extra)));
    }
}
