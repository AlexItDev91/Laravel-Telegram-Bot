<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramStickerData extends TelegramObjectData
{
    public function fileId(): ?string
    {
        return $this->string('file_id');
    }

    public function fileUniqueId(): ?string
    {
        return $this->string('file_unique_id');
    }

    public function type(): ?string
    {
        return $this->string('type');
    }

    public function width(): ?int
    {
        return $this->int('width');
    }

    public function height(): ?int
    {
        return $this->int('height');
    }

    public function emoji(): ?string
    {
        return $this->string('emoji');
    }

    public function setName(): ?string
    {
        return $this->string('set_name');
    }
}
