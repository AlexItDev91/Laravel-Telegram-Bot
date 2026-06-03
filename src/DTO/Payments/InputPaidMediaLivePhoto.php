<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\InputFile;

final readonly class InputPaidMediaLivePhoto implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string|InputFile $media,
        private string|InputFile $photo,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload([
            'type' => 'live_photo',
            'media' => $this->media,
            'photo' => $this->photo,
        ], $this->extra, ['type', 'media', 'photo']);
    }
}
