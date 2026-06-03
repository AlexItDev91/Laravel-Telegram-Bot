<?php

namespace App\Telegram\Middleware;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Closure;

final class EnsureTelegramWebhookEnabled implements TelegramWebhookMiddleware
{
    #[Override]
    public function process(TelegramWebhookUpdate $update, string $botName, Closure $next): mixed
    {
        if (config('services.telegram.webhook_enabled', true)) {
            return $next($update, $botName);
        }

        return ['ok' => true, 'disabled' => true];
    }
}
