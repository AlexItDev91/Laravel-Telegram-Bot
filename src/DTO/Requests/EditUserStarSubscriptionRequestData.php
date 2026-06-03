<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editUserStarSubscription`.
 */
final readonly class EditUserStarSubscriptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editUserStarSubscription';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        string $telegramPaymentChargeId,
        bool $isCanceled,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'telegram_payment_charge_id' => $telegramPaymentChargeId,
            'is_canceled' => $isCanceled,
        ], $extra)));
    }
}
