<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramStickerType;

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

    public function stickerTypeEnum(): ?TelegramStickerType
    {
        $type = $this->stickerType();

        return $type !== null ? TelegramStickerType::tryFrom($type) : null;
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
