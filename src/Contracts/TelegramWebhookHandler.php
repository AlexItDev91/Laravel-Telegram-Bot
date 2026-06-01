<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;

interface TelegramWebhookHandler
{
    public function handle(TelegramWebhookUpdate $update, string $botName): mixed;
}
