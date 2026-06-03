<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use Closure;

interface TelegramBotRateLimiter
{
    /**
     * @param  Closure(): mixed  $next
     */
    public function throttle(string $method, Closure $next): mixed;
}
