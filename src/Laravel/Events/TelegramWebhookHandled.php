<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Events;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;

final readonly class TelegramWebhookHandled
{
    public function __construct(
        public TelegramWebhookUpdate $update,
        public string $botName,
    ) {
        //
    }
}
