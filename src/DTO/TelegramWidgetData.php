<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramWidgetData extends TelegramObjectData
{
    public function id(): ?string
    {
        return $this->string('id');
    }

    public function width(): ?int
    {
        return $this->int('width');
    }

    public function height(): ?int
    {
        return $this->int('height');
    }

    public function html(): ?string
    {
        return $this->string('html');
    }

    public function publicData(): ?string
    {
        return $this->string('public_data');
    }

    public function chatData(): ?string
    {
        return $this->string('chat_data');
    }
}
