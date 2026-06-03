<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Concerns\ResolvesTelegramCacheRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;

readonly class TelegramWebhookIdempotency
{
    use ResolvesTelegramCacheRepository;

    public function __construct(
        private readonly Container $container,
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    public function shouldSkip(TelegramWebhookUpdate $update, string $botName): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $updateId = $update->updateId();

        if ($updateId === null) {
            return false;
        }

        $cache = $this->cache();

        if ($cache === null) {
            $this->warning('Telegram webhook idempotency is enabled but no cache repository is available.', [
                'bot' => $botName,
                'update_id' => $updateId,
                'update_type' => $update->type(),
            ]);

            return false;
        }

        return ! $cache->add($this->key($botName, $updateId), true, $this->ttl());
    }

    public function release(TelegramWebhookUpdate $update, string $botName): void
    {
        if (! $this->enabled()) {
            return;
        }

        $updateId = $update->updateId();
        $cache = $this->cache();

        if ($updateId === null || $cache === null) {
            return;
        }

        $cache->forget($this->key($botName, $updateId));
    }

    private function enabled(): bool
    {
        return config('telegram-bot.webhook.idempotency.enabled', false) ? true : false;
    }

    private function ttl(): int
    {
        $ttl = (int) config('telegram-bot.webhook.idempotency.ttl', 86400);

        return $ttl > 0 ? $ttl : 86400;
    }

    private function key(string $botName, int $updateId): string
    {
        return 'telegram-bot:webhook:'.$botName.':'.$updateId;
    }

    private function cache(): ?CacheRepository
    {
        return $this->cacheRepository(config('telegram-bot.webhook.idempotency.store'));
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
