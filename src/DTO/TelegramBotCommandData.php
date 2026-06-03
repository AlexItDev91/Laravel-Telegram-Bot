<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotCommandData extends TelegramObjectData
{
    public function command(): ?string
    {
        return $this->string('command');
    }

    public function description(): ?string
    {
        return $this->string('description');
    }
}
