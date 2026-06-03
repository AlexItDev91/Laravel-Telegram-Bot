<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramOwnedGiftsData extends TelegramObjectData
{
    public function totalCount(): ?int
    {
        return $this->int('total_count');
    }

    public function nextOffset(): ?string
    {
        return $this->string('next_offset');
    }

    /**
     * @return list<TelegramObjectData>
     */
    public function gifts(): array
    {
        return array_map(
            static fn (array $gift): TelegramObjectData => TelegramGenericObjectData::fromPayload($gift),
            $this->list('gifts'),
        );
    }
}
