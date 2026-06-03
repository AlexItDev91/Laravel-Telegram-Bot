<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Conversation;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;

final readonly class TelegramConversationWorkflow
{
    private const string EXPIRES_AT = '_expires_at';

    public function __construct(
        private TelegramConversationManager $manager,
        private string $key,
    ) {
        //
    }

    public function current(): ?TelegramConversationData
    {
        return $this->manager->get($this->key);
    }

    public function state(): ?string
    {
        return $this->current()?->state();
    }

    public function is(string $state): bool
    {
        return $this->state() === $state;
    }

    public function context(): TelegramConversationContext
    {
        return TelegramConversationContext::fromConversation($this->current());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function start(string $state, array $data = [], ?int $ttl = null, ?int $timeoutSeconds = null): TelegramConversationData
    {
        return $this->manager->put($this->key, $state, $this->withTimeout($data, $timeoutSeconds), $ttl ?? $timeoutSeconds);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function advance(string $state, array $data = [], ?int $ttl = null): TelegramConversationData
    {
        return $this->manager->put($this->key, $state, $this->context()->merge($data)->toArray(), $ttl);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function transition(TelegramConversationTransition $transition, array $data = [], ?TelegramWebhookUpdate $update = null): ?TelegramConversationData
    {
        $current = $this->current();

        if ($current === null || $current->state() !== $transition->from || $this->isExpired()) {
            return null;
        }

        $context = TelegramConversationContext::fromConversation($current);

        if (! $transition->allows($context, $update)) {
            return null;
        }

        return $this->manager->put(
            $this->key,
            $transition->to,
            $context->merge($data)->toArray(),
            $transition->ttl,
        );
    }

    public function timeout(int $seconds): ?TelegramConversationData
    {
        $current = $this->current();

        if ($current === null) {
            return null;
        }

        return $this->manager->put(
            $this->key,
            $current->state(),
            $this->withTimeout($current->data(), $seconds),
            $seconds,
        );
    }

    public function isExpired(?int $now = null): bool
    {
        $expiresAt = $this->context()->int(self::EXPIRES_AT);

        return $expiresAt !== null && ($now ?? time()) >= $expiresAt;
    }

    public function reset(): void
    {
        $this->manager->forget($this->key);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withTimeout(array $data, ?int $seconds): array
    {
        if ($seconds === null) {
            return $data;
        }

        return array_merge($data, [self::EXPIRES_AT => time() + $seconds]);
    }
}
