<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;

interface TelegramConversationStore
{
    public function get(string $key): ?TelegramConversationData;

    /**
     * @param  array<string, mixed>  $data
     */
    public function put(string $key, string $state, array $data = [], ?int $ttl = null): TelegramConversationData;

    public function forget(string $key): void;
}
