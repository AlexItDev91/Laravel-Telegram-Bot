<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramStarTransactionData extends TelegramObjectData
{
    public function id(): ?string
    {
        return $this->string('id');
    }

    public function amount(): ?int
    {
        return $this->int('amount');
    }

    public function date(): ?int
    {
        return $this->int('date');
    }

    public function source(): ?TelegramGenericObjectData
    {
        $source = $this->object('source');

        return $source !== null ? TelegramGenericObjectData::fromPayload($source) : null;
    }

    public function receiver(): ?TelegramGenericObjectData
    {
        $receiver = $this->object('receiver');

        return $receiver !== null ? TelegramGenericObjectData::fromPayload($receiver) : null;
    }
}
