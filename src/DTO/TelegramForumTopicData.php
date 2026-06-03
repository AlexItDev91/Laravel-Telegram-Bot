<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramForumTopicData extends TelegramObjectData
{
    public function messageThreadId(): ?int
    {
        return $this->int('message_thread_id');
    }

    public function name(): ?string
    {
        return $this->string('name');
    }

    public function iconColor(): ?int
    {
        return $this->int('icon_color');
    }

    public function iconCustomEmojiId(): ?string
    {
        return $this->string('icon_custom_emoji_id');
    }
}
