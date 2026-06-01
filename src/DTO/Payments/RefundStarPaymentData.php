<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class RefundStarPaymentData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $userId,
        string $telegramPaymentChargeId,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'user_id' => $userId,
            'telegram_payment_charge_id' => $telegramPaymentChargeId,
        ], $extra));
    }
}
