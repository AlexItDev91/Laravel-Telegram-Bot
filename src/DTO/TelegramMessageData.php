<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramMessageData implements TelegramBotData
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

    public function messageId(): ?int
    {
        return $this->intAt('message_id');
    }

    public function messageThreadId(): ?int
    {
        return $this->intAt('message_thread_id');
    }

    public function date(): ?int
    {
        return $this->intAt('date');
    }

    public function text(): ?string
    {
        return $this->stringAt('text');
    }

    public function caption(): ?string
    {
        return $this->stringAt('caption');
    }

    public function chat(): ?TelegramChatData
    {
        $chat = $this->payload['chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    public function from(): ?TelegramUserData
    {
        $from = $this->payload['from'] ?? null;

        return is_array($from) ? TelegramUserData::fromPayload($from) : null;
    }

    public function senderChat(): ?TelegramChatData
    {
        $senderChat = $this->payload['sender_chat'] ?? null;

        return is_array($senderChat) ? TelegramChatData::fromPayload($senderChat) : null;
    }

    public function isTopicMessage(): ?bool
    {
        return $this->boolAt('is_topic_message');
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

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }
}
