<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use Override;

final readonly class DisabledButton implements TelegramBotData
{
    /**
     * @return array<string, never>
     */
    #[Override]
    public function toArray(): array
    {
        return [];
    }
}
