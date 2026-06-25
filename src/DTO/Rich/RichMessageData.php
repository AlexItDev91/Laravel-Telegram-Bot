<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class RichMessageData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(private array $payload)
    {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function blocks(): array
    {
        $blocks = $this->payload['blocks'] ?? null;

        if (! is_array($blocks)) {
            return [];
        }

        return array_values(array_filter($blocks, static fn (mixed $block): bool => is_array($block)));
    }

    /**
     * @return list<RichBlock>
     */
    public function blockData(): array
    {
        return array_map(
            static fn (array $block): RichBlock => RichBlock::fromArray($block),
            $this->blocks(),
        );
    }

    public function isRtl(): ?bool
    {
        $value = $this->payload['is_rtl'] ?? null;

        return is_bool($value) ? $value : null;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }
}
