<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramMenuButtonType;

final readonly class TelegramMenuButtonData extends TelegramObjectData
{
    public function type(): ?string
    {
        return $this->string('type');
    }

    public function typeEnum(): ?TelegramMenuButtonType
    {
        $type = $this->type();

        return $type !== null ? TelegramMenuButtonType::tryFrom($type) : null;
    }

    public function text(): ?string
    {
        return $this->string('text');
    }
}
