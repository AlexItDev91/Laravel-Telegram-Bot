<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUserData implements TelegramBotData
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

    public function isBot(): ?bool
    {
        return $this->boolAt('is_bot');
    }

    public function firstName(): ?string
    {
        return $this->stringAt('first_name');
    }

    public function lastName(): ?string
    {
        return $this->stringAt('last_name');
    }

    public function username(): ?string
    {
        return $this->stringAt('username');
    }

    public function languageCode(): ?string
    {
        return $this->stringAt('language_code');
    }

    public function isPremium(): ?bool
    {
        return $this->boolAt('is_premium');
    }

    public function supportsGuestQueries(): ?bool
    {
        return $this->boolAt('supports_guest_queries');
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }
}
