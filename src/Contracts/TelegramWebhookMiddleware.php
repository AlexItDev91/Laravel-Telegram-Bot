<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Closure;

interface TelegramWebhookMiddleware
{
    /**
     * @param  Closure(TelegramWebhookUpdate, string): mixed  $next
     */
    public function process(TelegramWebhookUpdate $update, string $botName, Closure $next): mixed;
}
