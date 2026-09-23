<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramMessageGenerationStoppedData extends TelegramObjectData
{
    public function chat(): ?TelegramChatData
    {
        $chat = $this->object('chat');

        return $chat !== null ? TelegramChatData::fromPayload($chat) : null;
    }

    public function messageThreadId(): ?int
    {
        return $this->int('message_thread_id');
    }

    public function draftId(): ?int
    {
        return $this->int('draft_id');
    }
}
