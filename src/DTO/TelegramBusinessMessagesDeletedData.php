<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
final readonly class TelegramBusinessMessagesDeletedData implements TelegramBotData
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

    public function businessConnectionId(): ?string
    {
        return $this->stringAt('business_connection_id');
    }

    public function chat(): ?TelegramChatData
    {
        $chat = $this->payload['chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    /**
     * @return list<int>
     */
    public function messageIds(): array
    {
        $messageIds = $this->payload['message_ids'] ?? null;

        if (! is_array($messageIds)) {
            return [];
        }

        return array_values(array_filter($messageIds, static fn (mixed $messageId): bool => is_int($messageId)));
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
