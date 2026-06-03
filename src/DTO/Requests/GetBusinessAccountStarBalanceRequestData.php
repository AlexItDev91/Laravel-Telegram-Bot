<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getBusinessAccountStarBalance`.
 */
final readonly class GetBusinessAccountStarBalanceRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getBusinessAccountStarBalance';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
        ], $extra)));
    }
}
