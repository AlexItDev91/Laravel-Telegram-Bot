<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramConversationStore;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;
use AlexItDev91\LaravelTelegramBot\Laravel\Concerns\ResolvesTelegramCacheRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;

readonly class TelegramConversationCacheStore implements TelegramConversationStore
{
    use ResolvesTelegramCacheRepository;

    public function __construct(
        private readonly Container $container,
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    #[\Override]
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
    #[\Override]
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

    #[\Override]
    public function forget(string $key): void
    {
        if (! $this->enabled()) {
            return;
        }

        $this->cache()?->forget($key);
    }

    private function enabled(): bool
    {
        return config('telegram-bot.conversation.enabled', false) ? true : false;
    }

    private function ttl(): int
    {
        $ttl = (int) config('telegram-bot.conversation.ttl', 86400);

        return $ttl > 0 ? $ttl : 86400;
    }

    private function cache(): ?CacheRepository
    {
        return $this->cacheRepository(config('telegram-bot.conversation.store'));
    }

    private function container(): Container
    {
        return $this->container;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function warning(string $message, array $context): void
    {
        if (! config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->warning($message, $context);
    }
}
