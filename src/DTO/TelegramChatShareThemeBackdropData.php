<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatShareThemeBackdropData extends TelegramObjectData
{
    public function id(): ?int
    {
        return $this->int('id');
    }

    public function name(): ?string
    {
        return $this->string('name');
    }

    public function colors(): ?TelegramUniqueGiftBackdropColorsData
    {
        $value = $this->object('colors');

        return $value !== null ? TelegramUniqueGiftBackdropColorsData::fromPayload($value) : null;
    }
}
