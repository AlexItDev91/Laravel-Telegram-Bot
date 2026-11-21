<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramWidgetQueryWalletData extends TelegramObjectData
{
    public function address(): ?string
    {
        return $this->string('address');
    }

    public function amount(): ?string
    {
        return $this->string('amount');
    }

    public function publicKey(): ?string
    {
        return $this->string('public_key');
    }

    public function stateInit(): ?string
    {
        return $this->string('state_init');
    }
}
