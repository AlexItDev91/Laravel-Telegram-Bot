<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotNameData extends TelegramObjectData
{
    public function name(): ?string
    {
        return $this->string('name');
    }
}
