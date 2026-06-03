<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotRateLimiter as TelegramBotRateLimiterContract;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotRateLimitException;
use AlexItDev91\LaravelTelegramBot\Laravel\Concerns\ResolvesTelegramCacheRepository;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Container\Container;

readonly class TelegramBotRateLimiter implements TelegramBotRateLimiterContract
{
    use ResolvesTelegramCacheRepository;

    public function __construct(private Container $container)
    {
        //
    }

    public function throttle(string $method, Closure $next): mixed
    {
        if (! $this->enabled()) {
            return $next();
        }

        $cache = $this->cache();

        if (! $cache instanceof CacheRepository) {
            return $next();
        }

        $key = $this->key($method);
        $window = $this->windowSeconds();
        $now = time();
        $bucket = $cache->get($key);
        $bucket = is_array($bucket) ? $bucket : [];
        $resetAt = is_int($bucket['reset_at'] ?? null) ? $bucket['reset_at'] : $now + $window;
        $attempts = is_int($bucket['attempts'] ?? null) ? $bucket['attempts'] : 0;

        if ($resetAt <= $now) {
            $resetAt = $now + $window;
            $attempts = 0;
        }

        if ($attempts >= $this->maxAttempts()) {
            throw new TelegramBotRateLimitException(
                sprintf('Telegram Bot API method [%s] is locally rate limited.', $method),
                max(1, $resetAt - $now),
            );
        }

        $cache->put($key, [
            'attempts' => $attempts + 1,
            'reset_at' => $resetAt,
        ], max(1, $resetAt - $now));

        return $next();
    }

    private function enabled(): bool
    {
        return config('telegram-bot.rate_limit.enabled', false) ? true : false;
    }

    private function maxAttempts(): int
    {
        return max(1, (int) config('telegram-bot.rate_limit.max_attempts', 30));
    }

    private function windowSeconds(): int
    {
        return max(1, (int) config('telegram-bot.rate_limit.decay_seconds', 1));
    }

    private function key(string $method): string
    {
        $prefix = config('telegram-bot.rate_limit.key_prefix', 'telegram-bot:rate-limit');
        $prefix = is_string($prefix) && $prefix !== '' ? $prefix : 'telegram-bot:rate-limit';

        return $prefix.':'.preg_replace('/[^A-Za-z0-9:_-]+/', '_', $method);
    }

    private function cache(): ?CacheRepository
    {
        return $this->cacheRepository(config('telegram-bot.rate_limit.store'));
    }

    private function container(): Container
    {
        return $this->container;
    }
}
