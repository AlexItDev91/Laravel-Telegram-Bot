<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramConversationStore;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWorkflow;

readonly class TelegramConversationManager
{
    public function __construct(private TelegramConversationStore $store)
    {
        //
    }

    public function get(string $key): ?TelegramConversationData
    {
        return $this->store->get($key);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function put(string $key, string $state, array $data = [], ?int $ttl = null): TelegramConversationData
    {
        return $this->store->put($key, $state, $data, $ttl);
    }

    public function forget(string $key): void
    {
        $this->store->forget($key);
    }

    public function workflow(string $key): TelegramConversationWorkflow
    {
        return new TelegramConversationWorkflow($this, $key);
    }

    public function forUpdate(TelegramWebhookUpdate $update, string $botName): ?TelegramConversationData
    {
        return $this->get($this->keyForUpdate($update, $botName));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function putForUpdate(TelegramWebhookUpdate $update, string $botName, string $state, array $data = [], ?int $ttl = null): TelegramConversationData
    {
        return $this->put($this->keyForUpdate($update, $botName), $state, $data, $ttl);
    }

    public function forgetForUpdate(TelegramWebhookUpdate $update, string $botName): void
    {
        $this->forget($this->keyForUpdate($update, $botName));
    }

    public function workflowForUpdate(TelegramWebhookUpdate $update, string $botName): TelegramConversationWorkflow
    {
        return $this->workflow($this->keyForUpdate($update, $botName));
    }

    public function keyForUpdate(TelegramWebhookUpdate $update, string $botName): string
    {
        $chatId = $update->effectiveChat()?->id();
        $userId = $update->effectiveUser()?->id();
        $updateId = $update->updateId();
        $subject = $chatId !== null || $userId !== null
            ? sprintf('chat:%s:user:%s', $chatId !== null ? (string) $chatId : 'none', $userId !== null ? (string) $userId : 'none')
            : sprintf('update:%s', $updateId ?? 'unknown');

        return implode(':', [
            $this->prefix(),
            $this->normalize($botName),
            $this->normalize($subject),
        ]);
    }

    private function prefix(): string
    {
        $prefix = config('telegram-bot.conversation.key_prefix', 'telegram-bot:conversation');

        return is_string($prefix) && $prefix !== '' ? $prefix : 'telegram-bot:conversation';
    }

    private function normalize(string $value): string
    {
        return preg_replace('/[^A-Za-z0-9:_-]+/', '_', $value) ?? $value;
    }
}
