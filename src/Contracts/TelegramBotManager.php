<?php

namespace Aptenova\TelegramBot\Contracts;

use Aptenova\TelegramBot\TelegramBotChannel;

interface TelegramBotManager
{
    public function bot(?string $name = null): TelegramBotClient;

    public function channel(string $name): TelegramBotChannel;
}
