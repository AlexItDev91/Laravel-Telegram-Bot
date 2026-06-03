<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUserProfileAudiosData extends TelegramObjectData
{
    public function totalCount(): ?int
    {
        return $this->int('total_count');
    }

    /**
     * @return list<TelegramGenericObjectData>
     */
    public function audios(): array
    {
        return array_map(
            static fn (array $audio): TelegramGenericObjectData => TelegramGenericObjectData::fromPayload($audio),
            $this->list('audios'),
        );
    }
}
