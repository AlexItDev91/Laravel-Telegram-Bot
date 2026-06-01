<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class ShippingOption implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, LabeledPrice|array<string, mixed>>  $prices
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string $id,
        private string $title,
        private array $prices,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return self::payload([
            'id' => $this->id,
            'title' => $this->title,
            'prices' => $this->prices,
        ], $this->extra);
    }
}
