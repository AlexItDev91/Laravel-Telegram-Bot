<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramMessageReactionUpdatedData implements TelegramBotData
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

    public function user(): ?TelegramUserData
    {
        $user = $this->payload['user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    public function actorChat(): ?TelegramChatData
    {
        $chat = $this->payload['actor_chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    public function date(): ?int
    {
        return $this->intAt('date');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function oldReaction(): array
    {
        return $this->listOfArraysAt('old_reaction');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function newReaction(): array
    {
        return $this->listOfArraysAt('new_reaction');
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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
