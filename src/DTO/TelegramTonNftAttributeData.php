<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramTonNftAttributeData extends TelegramObjectData
{
    public function traitType(): ?string
    {
        return $this->string('trait_type');
    }

    public function value(): ?string
    {
        return $this->string('value');
    }
}
