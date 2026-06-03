<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `answerPreCheckoutQuery`.
 */
final readonly class AnswerPreCheckoutQueryRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'answerPreCheckoutQuery';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $preCheckoutQueryId,
        bool $ok,
        ?string $errorMessage = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'pre_checkout_query_id' => $preCheckoutQueryId,
            'ok' => $ok,
            'error_message' => $errorMessage,
        ], $extra)));
    }
}
