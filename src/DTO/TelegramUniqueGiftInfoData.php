<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUniqueGiftInfoData extends TelegramObjectData
{
    public function text(): ?string
    {
        return $this->string('text');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function entities(): array
    {
        return array_map(
            static fn (array $entity): TelegramMessageEntityData => TelegramMessageEntityData::fromPayload($entity),
            $this->list('entities'),
        );
    }

    public function isPrivate(): ?bool
    {
        return $this->bool('is_private');
    }
}
