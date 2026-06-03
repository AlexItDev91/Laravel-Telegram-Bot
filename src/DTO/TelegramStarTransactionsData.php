<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramStarTransactionsData extends TelegramObjectData
{
    /**
     * @return list<TelegramStarTransactionData>
     */
    public function transactions(): array
    {
        return array_map(
            static fn (array $transaction): TelegramStarTransactionData => TelegramStarTransactionData::fromPayload($transaction),
            $this->list('transactions'),
        );
    }
}
