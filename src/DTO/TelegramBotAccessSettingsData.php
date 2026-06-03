<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotAccessSettingsData extends TelegramObjectData
{
    public function type(): ?string
    {
        return $this->string('type');
    }
}
