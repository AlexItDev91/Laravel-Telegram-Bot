<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramCallbackQueryData implements TelegramBotData
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

    public function from(): ?TelegramUserData
    {
        $from = $this->payload['from'] ?? null;

        return is_array($from) ? TelegramUserData::fromPayload($from) : null;
    }

    public function message(): ?TelegramMessageData
    {
        $message = $this->payload['message'] ?? null;

        return is_array($message) ? TelegramMessageData::fromPayload($message) : null;
    }

    public function inlineMessageId(): ?string
    {
        return $this->stringAt('inline_message_id');
    }

    public function chatInstance(): ?string
    {
        return $this->stringAt('chat_instance');
    }

    public function data(): ?string
    {
        return $this->stringAt('data');
    }

    public function gameShortName(): ?string
    {
        return $this->stringAt('game_short_name');
    }

    /**
     * @return array<string, mixed>
     */
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
