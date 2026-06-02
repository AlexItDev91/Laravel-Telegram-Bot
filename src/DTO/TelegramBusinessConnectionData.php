<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBusinessConnectionData implements TelegramBotData
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

    public function id(): ?string
    {
        return $this->stringAt('id');
    }

    public function user(): ?TelegramUserData
    {
        $user = $this->payload['user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    public function userChatId(): int|string|null
    {
        return $this->identifierAt('user_chat_id');
    }

    public function date(): ?int
    {
        return $this->intAt('date');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function rights(): ?array
    {
        return $this->arrayAt('rights');
    }

    public function isEnabled(): ?bool
    {
        return $this->boolAt('is_enabled');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) ? $value : null;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    private function identifierAt(string $key): int|string|null
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) || is_string($value) ? $value : null;
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
