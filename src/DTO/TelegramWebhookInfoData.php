<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramWebhookInfoData implements TelegramBotData
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

    public function url(): ?string
    {
        return $this->stringAt('url');
    }

    public function hasCustomCertificate(): ?bool
    {
        return $this->boolAt('has_custom_certificate');
    }

    public function pendingUpdateCount(): ?int
    {
        return $this->intAt('pending_update_count');
    }

    public function ipAddress(): ?string
    {
        return $this->stringAt('ip_address');
    }

    public function lastErrorDate(): ?int
    {
        return $this->intAt('last_error_date');
    }

    public function lastErrorMessage(): ?string
    {
        return $this->stringAt('last_error_message');
    }

    public function lastSynchronizationErrorDate(): ?int
    {
        return $this->intAt('last_synchronization_error_date');
    }

    public function maxConnections(): ?int
    {
        return $this->intAt('max_connections');
    }

    /**
     * @return list<string>
     */
    public function allowedUpdates(): array
    {
        $updates = $this->payload['allowed_updates'] ?? null;

        if (! is_array($updates)) {
            return [];
        }

        return array_values(array_filter($updates, static fn (mixed $update): bool => is_string($update)));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }
}
