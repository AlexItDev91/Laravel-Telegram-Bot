<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
final readonly class TelegramConversationData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private string $key,
        private string $state,
        private array $data = [],
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(string $key, array $payload): ?self
    {
        $state = $payload['state'] ?? null;
        $data = $payload['data'] ?? [];

        if (! is_string($state) || ! is_array($data)) {
            return null;
        }

        return new self($key, $state, $data);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function state(): string
    {
        return $this->state;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @return array{key: string, state: string, data: array<string, mixed>}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'state' => $this->state,
            'data' => $this->data,
        ];
    }
}
