<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class AnswerShippingQueryData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, ShippingOption|array<string, mixed>>|null  $shippingOptions
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        string $shippingQueryId,
        bool $ok,
        ?array $shippingOptions = null,
        ?string $errorMessage = null,
        array $extra = [],
    ) {
        $required = $ok
            ? ['shipping_query_id', 'shipping_options']
            : ['shipping_query_id', 'error_message'];

        parent::__construct(self::payload([
            'shipping_query_id' => $shippingQueryId,
            'ok' => $ok,
            'shipping_options' => $shippingOptions,
            'error_message' => $errorMessage,
        ], $extra, $required));
    }

    /**
     * @param  array<int, ShippingOption|array<string, mixed>>  $shippingOptions
     */
    public static function accept(string $shippingQueryId, array $shippingOptions): self
    {
        return new self($shippingQueryId, true, shippingOptions: $shippingOptions);
    }

    public static function reject(string $shippingQueryId, string $errorMessage): self
    {
        return new self($shippingQueryId, false, errorMessage: $errorMessage);
    }
}
