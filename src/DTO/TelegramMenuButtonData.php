<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramMenuButtonData extends TelegramObjectData
{
    public function type(): ?string
    {
        return $this->string('type');
    }

    public function text(): ?string
    {
        return $this->string('text');
    }
}
