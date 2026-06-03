<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `answerShippingQuery`.
 */
final readonly class AnswerShippingQueryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'answerShippingQuery';

    /**
     * @param  array<string|int, mixed>|null  $shippingOptions
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $shippingQueryId,
        bool $ok,
        ?array $shippingOptions = null,
        ?string $errorMessage = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'shipping_query_id' => $shippingQueryId,
            'ok' => $ok,
            'shipping_options' => $shippingOptions,
            'error_message' => $errorMessage,
        ], $extra)));
    }
}
