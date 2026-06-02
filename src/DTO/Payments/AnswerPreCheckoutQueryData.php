<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class AnswerPreCheckoutQueryData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        string $preCheckoutQueryId,
        bool $ok,
        ?string $errorMessage = null,
        array $extra = [],
    ) {
        $required = $ok
            ? ['pre_checkout_query_id']
            : ['pre_checkout_query_id', 'error_message'];

        parent::__construct(self::payload([
            'pre_checkout_query_id' => $preCheckoutQueryId,
            'ok' => $ok,
            'error_message' => $errorMessage,
        ], $extra, $required));
    }

    public static function accept(string $preCheckoutQueryId): self
    {
        return new self($preCheckoutQueryId, true);
    }

    public static function reject(string $preCheckoutQueryId, string $errorMessage): self
    {
        return new self($preCheckoutQueryId, false, $errorMessage);
    }
}
