<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUserChatBoostsData extends TelegramObjectData
{
    /**
     * @return list<array<string, mixed>>
     */
    public function boosts(): array
    {
        return $this->list('boosts');
    }
}
