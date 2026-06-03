<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `refundStarPayment`.
 */
final readonly class RefundStarPaymentRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'refundStarPayment';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        string $telegramPaymentChargeId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'telegram_payment_charge_id' => $telegramPaymentChargeId,
        ], $extra)));
    }
}
