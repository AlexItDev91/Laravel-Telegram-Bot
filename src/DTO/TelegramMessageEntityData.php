<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramMessageEntityType;

final readonly class TelegramMessageEntityData implements TelegramBotData
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

    public function type(): ?string
    {
        return $this->stringAt('type');
    }

    public function typeEnum(): ?TelegramMessageEntityType
    {
        $type = $this->type();

        return $type !== null ? TelegramMessageEntityType::tryFrom($type) : null;
    }

    public function offset(): ?int
    {
        return $this->intAt('offset');
    }

    public function length(): ?int
    {
        return $this->intAt('length');
    }

    public function url(): ?string
    {
        return $this->stringAt('url');
    }

    public function user(): ?TelegramUserData
    {
        $user = $this->payload['user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    public function language(): ?string
    {
        return $this->stringAt('language');
    }

    public function customEmojiId(): ?string
    {
        return $this->stringAt('custom_emoji_id');
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
