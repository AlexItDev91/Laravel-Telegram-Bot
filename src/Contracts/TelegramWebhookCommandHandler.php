<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookCommand;

interface TelegramWebhookCommandHandler
{
    public function handle(TelegramWebhookCommand $command, TelegramWebhookUpdate $update, string $botName): mixed;
}
