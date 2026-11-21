<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramEmojiTypeData extends TelegramObjectData
{
    public function type(): ?string
    {
        return $this->string('type');
    }

    public function emoji(): ?string
    {
        return $this->string('emoji');
    }

    public function customEmojiId(): ?string
    {
        return $this->string('custom_emoji_id');
    }
}
