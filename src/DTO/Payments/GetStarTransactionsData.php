<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class GetStarTransactionsData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        ?int $offset = null,
        ?int $limit = null,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'offset' => $offset,
            'limit' => $limit,
        ], $extra));
    }
}
