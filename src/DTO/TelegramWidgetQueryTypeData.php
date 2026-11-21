<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramWidgetQueryType;

final readonly class TelegramWidgetQueryTypeData extends TelegramObjectData
{
    public function type(): ?string
    {
        return $this->string('type');
    }

    public function typeEnum(): ?TelegramWidgetQueryType
    {
        $type = $this->type();

        return $type !== null ? TelegramWidgetQueryType::tryFrom($type) : null;
    }

    public function walletCard(): ?TelegramTonWalletCardData
    {
        $value = $this->object('wallet_card');

        return $value !== null ? TelegramTonWalletCardData::fromPayload($value) : null;
    }

    public function wallet(): ?TelegramWidgetQueryWalletData
    {
        $value = $this->object('wallet');

        return $value !== null ? TelegramWidgetQueryWalletData::fromPayload($value) : null;
    }

    public function signData(): ?string
    {
        return $this->string('sign_data');
    }

    public function msgHash(): ?string
    {
        return $this->string('msg_hash');
    }
}
