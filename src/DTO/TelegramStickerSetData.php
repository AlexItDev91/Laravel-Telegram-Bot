<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramStickerSetData extends TelegramObjectData
{
    public function name(): ?string
    {
        return $this->string('name');
    }

    public function title(): ?string
    {
        return $this->string('title');
    }

    public function stickerType(): ?string
    {
        return $this->string('sticker_type');
    }

    /**
     * @return list<TelegramStickerData>
     */
    public function stickers(): array
    {
        return array_map(
            static fn (array $sticker): TelegramStickerData => TelegramStickerData::fromPayload($sticker),
            $this->list('stickers'),
        );
    }
}
