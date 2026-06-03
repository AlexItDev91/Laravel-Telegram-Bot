<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramPreparedInlineMessageData extends TelegramObjectData
{
    public function id(): ?string
    {
        return $this->string('id');
    }

    public function expirationDate(): ?int
    {
        return $this->int('expiration_date');
    }
}
