<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramManagedBotUpdatedData implements TelegramBotData
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

    public function user(): ?TelegramUserData
    {
        $user = $this->payload['user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    public function bot(): ?TelegramUserData
    {
        $bot = $this->payload['bot'] ?? null;

        return is_array($bot) ? TelegramUserData::fromPayload($bot) : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
