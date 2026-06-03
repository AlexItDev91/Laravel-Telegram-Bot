<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramPaidMediaType;
use AlexItDev91\LaravelTelegramBot\InputFile;

final readonly class InputPaidMediaVideo implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string|InputFile $media,
        private string|InputFile|null $thumbnail = null,
        private string|InputFile|null $cover = null,
        private ?int $startTimestamp = null,
        private ?int $width = null,
        private ?int $height = null,
        private ?int $duration = null,
        private ?bool $supportsStreaming = null,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        foreach ([
            'start_timestamp' => $this->startTimestamp,
            'width' => $this->width,
            'height' => $this->height,
            'duration' => $this->duration,
        ] as $field => $value) {
            if ($value !== null) {
                self::assertNonNegativeInteger($field, $value);
            }
        }

        return self::payload([
            'type' => TelegramPaidMediaType::Video,
            'media' => $this->media,
            'thumbnail' => $this->thumbnail,
            'cover' => $this->cover,
            'start_timestamp' => $this->startTimestamp,
            'width' => $this->width,
            'height' => $this->height,
            'duration' => $this->duration,
            'supports_streaming' => $this->supportsStreaming,
        ], $this->extra, ['type', 'media']);
    }
}
