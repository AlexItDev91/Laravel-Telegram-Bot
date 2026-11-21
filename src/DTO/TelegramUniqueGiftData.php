<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUniqueGiftData extends TelegramObjectData
{
    public function giftId(): ?string
    {
        return $this->string('gift_id');
    }

    public function baseName(): ?string
    {
        return $this->string('base_name');
    }

    public function name(): ?string
    {
        return $this->string('name');
    }

    public function number(): ?int
    {
        return $this->int('number');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function model(): ?array
    {
        return $this->object('model');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function symbol(): ?array
    {
        return $this->object('symbol');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function backdrop(): ?array
    {
        return $this->object('backdrop');
    }

    public function isPremium(): ?bool
    {
        return $this->bool('is_premium');
    }

    public function isBurned(): ?bool
    {
        return $this->bool('is_burned');
    }

    public function isFromBlockchain(): ?bool
    {
        return $this->bool('is_from_blockchain');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function colors(): ?array
    {
        return $this->object('colors');
    }

    public function publisherChat(): ?TelegramChatData
    {
        $value = $this->object('publisher_chat');

        return $value !== null ? TelegramChatData::fromPayload($value) : null;
    }
}
