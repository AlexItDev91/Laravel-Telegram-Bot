<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramPreparedKeyboardButtonData extends TelegramObjectData
{
    /**
     * @return array<string, mixed>|null
     */
    public function keyboardButton(): ?array
    {
        return $this->object('keyboard_button');
    }
}
