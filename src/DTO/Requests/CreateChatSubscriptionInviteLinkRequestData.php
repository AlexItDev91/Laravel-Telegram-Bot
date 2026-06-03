<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `createChatSubscriptionInviteLink`.
 */
final readonly class CreateChatSubscriptionInviteLinkRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'createChatSubscriptionInviteLink';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $subscriptionPeriod,
        int $subscriptionPrice,
        ?string $name = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'subscription_period' => $subscriptionPeriod,
            'subscription_price' => $subscriptionPrice,
            'name' => $name,
        ], $extra)));
    }
}
