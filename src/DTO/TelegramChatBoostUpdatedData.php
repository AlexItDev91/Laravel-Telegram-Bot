<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatBoostUpdatedData implements TelegramBotData
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

    /**
     * @return array<string, mixed>|null
     */
    public function boost(): ?array
    {
        $boost = $this->payload['boost'] ?? null;

        return is_array($boost) ? $boost : null;
    }

    public function boostData(): ?TelegramChatBoostData
    {
        $boost = $this->boost();

        return $boost !== null ? TelegramChatBoostData::fromPayload($boost) : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
