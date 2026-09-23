<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;
use Override;

final readonly class InputRichMessageMedia implements TelegramBotData
{
    private function __construct(
        private string $id,
        private TelegramBotData $media,
    ) {
        if (preg_match('/^[A-Za-z0-9_-]{1,64}$/', $id) !== 1) {
            throw new InvalidArgumentException('Telegram rich media ID must contain 1-64 letters, numbers, underscores, or hyphens.');
        }
    }

    public static function make(string $id, TelegramBotData $media): self
    {
        return new self($id, $media);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return ['id' => $this->id, 'media' => $this->media->toArray()];
    }
}
