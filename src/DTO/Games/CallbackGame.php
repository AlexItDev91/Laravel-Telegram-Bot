<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Games;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class CallbackGame implements TelegramBotData
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [];
    }
}
