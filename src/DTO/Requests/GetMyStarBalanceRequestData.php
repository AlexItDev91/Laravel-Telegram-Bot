<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getMyStarBalance`.
 */
final readonly class GetMyStarBalanceRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getMyStarBalance';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
