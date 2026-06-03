<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class SuggestedPostParameters implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $price
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private TelegramBotData|array|null $price = null,
        private ?int $sendDate = null,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload([
            'price' => $this->price,
            'send_date' => $this->sendDate,
        ], $this->extra);
    }
}
