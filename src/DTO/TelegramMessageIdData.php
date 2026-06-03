<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramMessageIdData extends TelegramObjectData
{
    public function messageId(): ?int
    {
        return $this->int('message_id');
    }
}
