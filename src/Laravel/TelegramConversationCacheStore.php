<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramConversationStore;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;

class TelegramConversationCacheStore implements TelegramConversationStore
{
    public function __construct(
        private readonly Container $container,
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    public function get(string $key): ?TelegramConversationData
    {
        if (! $this->enabled()) {
            return null;
        }

        $cache = $this->cache();

        if ($cache === null) {
            $this->warning('Telegram conversation store is enabled but no cache repository is available.', [
                'conversation_key' => $key,
            ]);

            return null;
        }

        $payload = $cache->get($key);

        return is_array($payload) ? TelegramConversationData::fromPayload($key, $payload) : null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function put(string $key, string $state, array $data = [], ?int $ttl = null): TelegramConversationData
    {
        $conversation = new TelegramConversationData($key, $state, $data);

        if (! $this->enabled()) {
            return $conversation;
        }

        $cache = $this->cache();

        if ($cache === null) {
            $this->warning('Telegram conversation store is enabled but no cache repository is available.', [
                'conversation_key' => $key,
            ]);

            return $conversation;
        }

        $cache->put($key, [
            'state' => $state,
            'data' => $data,
        ], $ttl ?? $this->ttl());

        return $conversation;
    }

    public function forget(string $key): void
    {
        if (! $this->enabled()) {
            return;
        }

        $this->cache()?->forget($key);
    }

    private function enabled(): bool
    {
        return (bool) config('telegram-bot.conversation.enabled', false);
    }

    private function ttl(): int
    {
        $ttl = (int) config('telegram-bot.conversation.ttl', 86400);

        return $ttl > 0 ? $ttl : 86400;
    }

    private function cache(): ?CacheRepository
    {
        $store = config('telegram-bot.conversation.store');

        if (is_string($store) && $store !== '' && $this->container->bound('cache')) {
            $cache = $this->container->make('cache');

            if (! method_exists($cache, 'store')) {
                return null;
            }

            $repository = $cache->store($store);

            return $repository instanceof CacheRepository ? $repository : null;
        }

        if ($this->container->bound(CacheRepository::class)) {
            return $this->container->make(CacheRepository::class);
        }

        if (! $this->container->bound('cache')) {
            return null;
        }

        $cache = $this->container->make('cache');

        if ($cache instanceof CacheRepository) {
            return $cache;
        }

        if (method_exists($cache, 'store')) {
            $repository = $cache->store();

            return $repository instanceof CacheRepository ? $repository : null;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function warning(string $message, array $context): void
    {
        if (! (bool) config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->warning($message, $context);
    }
}
