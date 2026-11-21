<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramTextQuoteData extends TelegramObjectData
{
    public function text(): ?string
    {
        return $this->string('text');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function entities(): array
    {
        return array_map(TelegramMessageEntityData::fromPayload(...), $this->list('entities'));
    }

    /**
     * @return list<string|array<int|string, mixed>>
     */
    public function richTexts(): array
    {
        $texts = $this->get('rich_texts');

        return is_array($texts)
            ? array_values(array_filter($texts, static fn (mixed $text): bool => is_string($text) || is_array($text)))
            : [];
    }

    public function position(): ?int
    {
        return $this->int('position');
    }

    public function isManual(): ?bool
    {
        return $this->bool('is_manual');
    }
}
