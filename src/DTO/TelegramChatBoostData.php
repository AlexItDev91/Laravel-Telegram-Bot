<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatBoostData implements TelegramBotData
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

    public function boostId(): ?string
    {
        return $this->stringAt('boost_id');
    }

    public function addDate(): ?int
    {
        return $this->intAt('add_date');
    }

    public function expirationDate(): ?int
    {
        return $this->intAt('expiration_date');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function source(): ?array
    {
        $source = $this->payload['source'] ?? null;

        return is_array($source) ? $source : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
