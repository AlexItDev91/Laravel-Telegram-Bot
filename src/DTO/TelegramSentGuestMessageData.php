<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramSentGuestMessageData extends TelegramObjectData
{
    public function messageId(): ?int
    {
        return $this->int('message_id');
    }
}
