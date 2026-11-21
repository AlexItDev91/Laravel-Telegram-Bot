<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramTonWalletCardStickerData extends TelegramObjectData
{
    public function position(): ?TelegramAreaPositionData
    {
        $value = $this->object('position');

        return $value !== null ? TelegramAreaPositionData::fromPayload($value) : null;
    }

    public function nft(): ?TelegramTonNftData
    {
        $value = $this->object('nft');

        return $value !== null ? TelegramTonNftData::fromPayload($value) : null;
    }
}
