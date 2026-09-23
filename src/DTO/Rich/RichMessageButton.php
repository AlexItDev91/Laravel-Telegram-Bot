<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramRichButtonStyle;
use InvalidArgumentException;
use Override;

final readonly class RichMessageButton implements TelegramBotData
{
    private function __construct(
        private string $text,
        private ?string $callbackData = null,
        private ?string $url = null,
        private ?TelegramRichButtonStyle $style = null,
    ) {
        if (trim($text) === '') {
            throw new InvalidArgumentException('Telegram rich button text must not be empty.');
        }

        if ($callbackData !== null && (strlen($callbackData) < 1 || strlen($callbackData) > 64)) {
            throw new InvalidArgumentException('Telegram rich button callback data must contain 1-64 bytes.');
        }
    }

    public static function callback(string $text, string $callbackData): self
    {
        return new self($text, callbackData: $callbackData);
    }

    public static function url(string $text, string $url): self
    {
        if (trim($url) === '') {
            throw new InvalidArgumentException('Telegram rich button URL must not be empty.');
        }

        return new self($text, url: $url);
    }

    public function withStyle(TelegramRichButtonStyle $style): self
    {
        if ($style === TelegramRichButtonStyle::Link && $this->callbackData === null) {
            throw new InvalidArgumentException('Telegram rich button link style is supported only for callback buttons.');
        }

        return new self($this->text, $this->callbackData, $this->url, $style);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
            'style' => $this->style?->value,
            'callback_data' => $this->callbackData,
            'url' => $this->url,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
