<?php

namespace AlexItDev91\LaravelTelegramBot\MiniApps;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class TelegramMiniAppChatData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(
        private array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }

    public function id(): int|string|null
    {
        return $this->identifierAt('id');
    }

    public function type(): ?string
    {
        return $this->stringAt('type');
    }

    public function title(): ?string
    {
        return $this->stringAt('title');
    }

    public function username(): ?string
    {
        return $this->stringAt('username');
    }

    public function photoUrl(): ?string
    {
        return $this->stringAt('photo_url');
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    private function identifierAt(string $key): int|string|null
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) || is_string($value) ? $value : null;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
