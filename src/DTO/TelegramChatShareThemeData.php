<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatShareThemeData extends TelegramObjectData
{
    public function type(): ?string
    {
        return $this->string('type');
    }

    /**
     * @return list<TelegramEmojiTypeData>
     */
    public function emojis(): array
    {
        return array_map(TelegramEmojiTypeData::fromPayload(...), $this->list('emojis'));
    }

    public function backgroundBackdropId(): ?int
    {
        return $this->int('background_backdrop_id');
    }

    public function gift(): ?TelegramUniqueGiftData
    {
        $value = $this->object('gift');

        return $value !== null ? TelegramUniqueGiftData::fromPayload($value) : null;
    }
}
