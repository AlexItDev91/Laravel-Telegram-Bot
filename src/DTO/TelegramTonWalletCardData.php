<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramTonWalletCardData extends TelegramObjectData
{
    public function address(): ?string
    {
        return $this->string('address');
    }

    public function amount(): ?string
    {
        return $this->string('amount');
    }

    /**
     * @return list<TelegramTonWalletCardStickerData>
     */
    public function stickers(): array
    {
        return array_map(TelegramTonWalletCardStickerData::fromPayload(...), $this->list('stickers'));
    }

    public function backgroundNftAddress(): ?string
    {
        return $this->string('background_nft_address');
    }
}
