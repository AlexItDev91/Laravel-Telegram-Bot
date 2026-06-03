<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class SuggestedPostPrice implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string $currency,
        private int $amount,
        private array $extra = [],
    ) {
        self::assertPositiveInteger('amount', $this->amount);
    }

    public static function stars(int $amount): self
    {
        return new self('XTR', $amount);
    }

    public static function ton(int $nanotons): self
    {
        return new self('TON', $nanotons);
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload([
            'currency' => $this->currency,
            'amount' => $this->amount,
        ], $this->extra, ['currency', 'amount']);
    }
}
