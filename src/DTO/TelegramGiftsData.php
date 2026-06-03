<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramGiftsData extends TelegramObjectData
{
    /**
     * @return list<TelegramGiftData>
     */
    public function gifts(): array
    {
        return array_map(
            static fn (array $gift): TelegramGiftData => TelegramGiftData::fromPayload($gift),
            $this->list('gifts'),
        );
    }
}
