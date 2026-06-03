<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getStarTransactions`.
 */
final readonly class GetStarTransactionsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getStarTransactions';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?int $offset = null,
        ?int $limit = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'offset' => $offset,
            'limit' => $limit,
        ], $extra)));
    }
}
