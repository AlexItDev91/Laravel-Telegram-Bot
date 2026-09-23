<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramCommunityData extends TelegramObjectData
{
    public function id(): int|string|null
    {
        return $this->identifier('id');
    }

    public function name(): ?string
    {
        return $this->string('name');
    }
}
