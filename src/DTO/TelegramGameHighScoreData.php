<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramGameHighScoreData extends TelegramObjectData
{
    public function position(): ?int
    {
        return $this->int('position');
    }

    public function user(): ?TelegramUserData
    {
        $user = $this->object('user');

        return $user !== null ? TelegramUserData::fromPayload($user) : null;
    }

    public function score(): ?int
    {
        return $this->int('score');
    }
}
