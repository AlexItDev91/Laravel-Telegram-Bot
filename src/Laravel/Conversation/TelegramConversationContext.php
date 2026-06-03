<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Conversation;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;

final readonly class TelegramConversationContext
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(private array $data = [])
    {
        //
    }

    public static function fromConversation(?TelegramConversationData $conversation): self
    {
        return new self($conversation?->data() ?? []);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function string(string $key, ?string $default = null): ?string
    {
        $value = $this->data[$key] ?? null;

        return is_string($value) ? $value : $default;
    }

    public function int(string $key, ?int $default = null): ?int
    {
        $value = $this->data[$key] ?? null;

        return is_int($value) ? $value : $default;
    }

    public function bool(string $key, ?bool $default = null): ?bool
    {
        $value = $this->data[$key] ?? null;

        return is_bool($value) ? $value : $default;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function array(string $key): ?array
    {
        $value = $this->data[$key] ?? null;

        return is_array($value) ? $value : null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function merge(array $data): self
    {
        return new self(array_merge($this->data, $data));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
