<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `answerCallbackQuery`.
 */
final readonly class AnswerCallbackQueryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'answerCallbackQuery';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $callbackQueryId,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => $showAlert,
            'url' => $url,
            'cache_time' => $cacheTime,
        ], $extra)));
    }
}
