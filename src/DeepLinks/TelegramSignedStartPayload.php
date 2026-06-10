<?php

namespace AlexItDev91\LaravelTelegramBot\DeepLinks;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class TelegramSignedStartPayload implements TelegramBotData
{
    /**
     * @param  string|array<string, mixed>  $payload
     */
    public function __construct(
        private string|array $payload,
        private ?int $expiresAt = null,
    ) {
        //
    }

    /**
     * @return string|array<string, mixed>
     */
    public function payload(): string|array
    {
        return $this->payload;
    }

    public function string(): ?string
    {
        return is_string($this->payload) ? $this->payload : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function array(): ?array
    {
        return is_array($this->payload) ? $this->payload : null;
    }

    public function expiresAt(): ?int
    {
        return $this->expiresAt;
    }

    public function expired(int $now): bool
    {
        return $this->expiresAt !== null && $now > $this->expiresAt;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'payload' => $this->payload,
            'expires_at' => $this->expiresAt,
        ];
    }
}
