<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotDescriptionData extends TelegramObjectData
{
    public function description(): ?string
    {
        return $this->string('description');
    }
}
