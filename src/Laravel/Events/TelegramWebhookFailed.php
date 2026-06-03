<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Events;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Throwable;

final readonly class TelegramWebhookFailed
{
    public function __construct(
        public TelegramWebhookUpdate $update,
        public string $botName,
        public Throwable $exception,
    ) {
        //
    }
}
