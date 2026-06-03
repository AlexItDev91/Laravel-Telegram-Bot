<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramGiftData extends TelegramObjectData
{
    public function id(): ?string
    {
        return $this->string('id');
    }

    public function starCount(): ?int
    {
        return $this->int('star_count');
    }

    public function totalCount(): ?int
    {
        return $this->int('total_count');
    }

    public function remainingCount(): ?int
    {
        return $this->int('remaining_count');
    }

    public function sticker(): ?TelegramStickerData
    {
        $sticker = $this->object('sticker');

        return $sticker !== null ? TelegramStickerData::fromPayload($sticker) : null;
    }
}
