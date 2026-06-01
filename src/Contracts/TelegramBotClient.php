<?php

namespace Aptenova\TelegramBot\Contracts;

use Aptenova\TelegramBot\DTO\TelegramBotRequestData;
use Aptenova\TelegramBot\Enums\TelegramBotApiMethod;

interface TelegramBotClient
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed;
}
