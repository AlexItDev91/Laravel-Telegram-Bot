<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotSubscriptionState;

final readonly class TelegramBotSubscriptionUpdatedData extends TelegramObjectData
{
    public function user(): ?TelegramUserData
    {
        $user = $this->object('user');

        return $user !== null ? TelegramUserData::fromPayload($user) : null;
    }

    public function invoicePayload(): ?string
    {
        return $this->string('invoice_payload');
    }

    public function state(): ?string
    {
        return $this->string('state');
    }

    public function stateEnum(): ?TelegramBotSubscriptionState
    {
        $state = $this->state();

        return $state !== null ? TelegramBotSubscriptionState::tryFrom($state) : null;
    }
}
