<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatBoostRemovedData implements TelegramBotData
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

    public function boostId(): ?string
    {
        return $this->stringAt('boost_id');
    }

    public function removeDate(): ?int
    {
        return $this->intAt('remove_date');
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

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
