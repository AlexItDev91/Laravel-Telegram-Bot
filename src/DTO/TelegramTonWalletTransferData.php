<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramTonWalletTransferData extends TelegramObjectData
{
    public function transactionId(): ?string
    {
        return $this->string('transaction_id');
    }

    public function peerAddress(): ?string
    {
        return $this->string('peer_address');
    }

    public function amount(): ?string
    {
        return $this->string('amount');
    }

    public function comment(): ?string
    {
        return $this->string('comment');
    }
}
