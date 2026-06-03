<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramStoryData extends TelegramObjectData
{
    public function chat(): ?TelegramChatData
    {
        $chat = $this->object('chat');

        return $chat !== null ? TelegramChatData::fromPayload($chat) : null;
    }

    public function id(): ?int
    {
        return $this->int('id');
    }
}
