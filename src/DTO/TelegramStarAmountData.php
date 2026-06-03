<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramStarAmountData extends TelegramObjectData
{
    public function amount(): ?int
    {
        return $this->int('amount');
    }

    public function nanostarAmount(): ?int
    {
        return $this->int('nanostar_amount');
    }
}
