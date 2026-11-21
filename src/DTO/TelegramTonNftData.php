<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramTonNftData extends TelegramObjectData
{
    public function address(): ?string
    {
        return $this->string('address');
    }

    public function collectionAddress(): ?string
    {
        return $this->string('collection_address');
    }

    public function index(): ?string
    {
        return $this->string('index');
    }

    public function name(): ?string
    {
        return $this->string('name');
    }

    public function description(): ?string
    {
        return $this->string('description');
    }

    /**
     * @return list<TelegramTonNftAttributeData>
     */
    public function attributes(): array
    {
        return array_map(TelegramTonNftAttributeData::fromPayload(...), $this->list('attributes'));
    }

    public function gift(): ?TelegramUniqueGiftData
    {
        $value = $this->object('gift');

        return $value !== null ? TelegramUniqueGiftData::fromPayload($value) : null;
    }
}
