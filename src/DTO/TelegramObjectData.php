<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

/**
 * @phpstan-consistent-constructor
 */
abstract readonly class TelegramObjectData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    protected function __construct(
        protected array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): static
    {
        return new static($payload);
    }

    public function get(string $key): mixed
    {
        return $this->payload[$key] ?? null;
    }

    public function string(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    public function int(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    public function identifier(string $key): int|string|null
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) || is_string($value) ? $value : null;
    }

    public function bool(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function object(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) && ! array_is_list($value) ? $value : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function list(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_array($item)));
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->payload;
    }
}
