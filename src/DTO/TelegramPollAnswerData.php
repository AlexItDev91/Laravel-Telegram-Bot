<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramPollAnswerData implements TelegramBotData
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

    public function pollId(): ?string
    {
        return $this->stringAt('poll_id');
    }

    public function voterChat(): ?TelegramChatData
    {
        $chat = $this->payload['voter_chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    public function user(): ?TelegramUserData
    {
        $user = $this->payload['user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    /**
     * @return list<int>
     */
    public function optionIds(): array
    {
        return $this->listOfIntegersAt('option_ids');
    }

    /**
     * @return list<string>
     */
    public function optionPersistentIds(): array
    {
        return $this->listOfStringsAt('option_persistent_ids');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @return list<int>
     */
    private function listOfIntegersAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_int($item)));
    }

    /**
     * @return list<string>
     */
    private function listOfStringsAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_string($item)));
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
