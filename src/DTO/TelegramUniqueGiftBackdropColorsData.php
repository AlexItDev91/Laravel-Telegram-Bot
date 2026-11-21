<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUniqueGiftBackdropColorsData extends TelegramObjectData
{
    public function centerColor(): ?int
    {
        return $this->int('center_color');
    }

    public function edgeColor(): ?int
    {
        return $this->int('edge_color');
    }

    public function symbolColor(): ?int
    {
        return $this->int('symbol_color');
    }

    public function textColor(): ?int
    {
        return $this->int('text_color');
    }
}
