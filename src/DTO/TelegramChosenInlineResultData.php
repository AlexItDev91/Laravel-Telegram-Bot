<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChosenInlineResultData implements TelegramBotData
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

    public function resultId(): ?string
    {
        return $this->stringAt('result_id');
    }

    public function from(): ?TelegramUserData
    {
        $from = $this->payload['from'] ?? null;

        return is_array($from) ? TelegramUserData::fromPayload($from) : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function location(): ?array
    {
        $location = $this->payload['location'] ?? null;

        return is_array($location) ? $location : null;
    }

    public function inlineMessageId(): ?string
    {
        return $this->stringAt('inline_message_id');
    }

    public function query(): ?string
    {
        return $this->stringAt('query');
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
