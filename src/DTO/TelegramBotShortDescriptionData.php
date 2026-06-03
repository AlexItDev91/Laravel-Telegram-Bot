<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotShortDescriptionData extends TelegramObjectData
{
    public function shortDescription(): ?string
    {
        return $this->string('short_description');
    }
}
