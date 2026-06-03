<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
final readonly class TelegramMessageReactionCountUpdatedData implements TelegramBotData
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

    public function chat(): ?TelegramChatData
    {
        $chat = $this->payload['chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    public function messageId(): ?int
    {
        return $this->intAt('message_id');
    }

    public function date(): ?int
    {
        return $this->intAt('date');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function reactions(): array
    {
        return $this->listOfArraysAt('reactions');
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listOfArraysAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_array($item)));
    }
}
