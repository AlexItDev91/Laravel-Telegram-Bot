<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;

interface TelegramBotManager
{
    public function bot(?string $name = null): TelegramBotClient;

    public function channel(string $name): TelegramBotChannel;
}
