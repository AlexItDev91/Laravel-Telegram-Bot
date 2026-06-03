<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramPaidMediaType;
use AlexItDev91\LaravelTelegramBot\InputFile;

final readonly class InputPaidMediaPhoto implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string|InputFile $media,
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
        return self::payload([
            'type' => TelegramPaidMediaType::Photo,
            'media' => $this->media,
        ], $this->extra, ['type', 'media']);
    }
}
