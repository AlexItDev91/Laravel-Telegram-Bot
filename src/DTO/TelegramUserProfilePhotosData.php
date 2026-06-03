<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramUserProfilePhotosData extends TelegramObjectData
{
    public function totalCount(): ?int
    {
        return $this->int('total_count');
    }

    /**
     * @return list<list<TelegramPhotoSizeData>>
     */
    public function photos(): array
    {
        $rows = $this->get('photos');

        if (! is_array($rows)) {
            return [];
        }

        return array_map(
            static fn (array $row): array => array_map(
                static fn (array $photo): TelegramPhotoSizeData => TelegramPhotoSizeData::fromPayload($photo),
                array_values(array_filter($row, static fn (mixed $photo): bool => is_array($photo))),
            ),
            array_values(array_filter($rows, static fn (mixed $row): bool => is_array($row))),
        );
    }
}
