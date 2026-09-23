<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramInputMediaType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\InputFile;
use InvalidArgumentException;
use Override;

final readonly class InputMediaVoiceNote implements TelegramBotData
{
    private function __construct(
        private string|InputFile $media,
        private ?string $caption = null,
        private TelegramParseMode|string|null $parseMode = null,
        private ?int $duration = null,
    ) {
        if (is_string($media) && trim($media) === '') {
            throw new InvalidArgumentException('Telegram voice note media must not be empty.');
        }
    }

    public static function fromFileId(string $fileId): self
    {
        return new self($fileId);
    }

    public static function fromInputFile(InputFile $file): self
    {
        return new self($file);
    }

    public function withCaption(string $caption, TelegramParseMode|string|null $parseMode = null): self
    {
        return new self($this->media, $caption, $parseMode, $this->duration);
    }

    public function withDuration(int $seconds): self
    {
        if ($seconds < 0) {
            throw new InvalidArgumentException('Telegram voice note duration must not be negative.');
        }

        return new self($this->media, $this->caption, $this->parseMode, $seconds);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'type' => TelegramInputMediaType::VoiceNote->value,
            'media' => $this->media,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode instanceof TelegramParseMode ? $this->parseMode->value : $this->parseMode,
            'duration' => $this->duration,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
