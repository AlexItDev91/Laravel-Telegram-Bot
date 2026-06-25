<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use Closure;

interface TelegramBotContextualRateLimiter extends TelegramBotRateLimiter
{
    /**
     * @param  Closure(): mixed  $next
     * @param  array<string, string|int|null>  $context
     */
    public function throttleWithContext(string $method, Closure $next, array $context = []): mixed;
}
