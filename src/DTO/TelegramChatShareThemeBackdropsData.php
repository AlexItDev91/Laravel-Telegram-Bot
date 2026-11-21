<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatShareThemeBackdropsData extends TelegramObjectData
{
    /**
     * @return list<TelegramChatShareThemeBackdropData>
     */
    public function backdrops(): array
    {
        return array_map(TelegramChatShareThemeBackdropData::fromPayload(...), $this->list('backdrops'));
    }
}
