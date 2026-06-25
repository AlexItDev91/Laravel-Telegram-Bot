<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;

final readonly class InputRichMessage implements TelegramBotData
{
    private function __construct(
        private ?string $html = null,
        private ?string $markdown = null,
        private ?bool $isRtl = null,
        private ?bool $skipEntityDetection = null,
    ) {
        if (($this->html === null) === ($this->markdown === null)) {
            throw new InvalidArgumentException('Telegram input rich messages require exactly one of [html] or [markdown].');
        }
    }

    public static function html(string $html): self
    {
        self::assertText('html', $html);

        return new self(html: $html);
    }

    public static function markdown(string $markdown): self
    {
        self::assertText('markdown', $markdown);

        return new self(markdown: $markdown);
    }

    public function rightToLeft(bool $isRtl = true): self
    {
        return new self($this->html, $this->markdown, $isRtl, $this->skipEntityDetection);
    }

    public function skipEntityDetection(bool $skip = true): self
    {
        return new self($this->html, $this->markdown, $this->isRtl, $skip);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'html' => $this->html,
            'markdown' => $this->markdown,
            'is_rtl' => $this->isRtl,
            'skip_entity_detection' => $this->skipEntityDetection,
        ], static fn (mixed $value): bool => $value !== null);
    }

    private static function assertText(string $field, string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Telegram input rich message field [$field] must not be empty.");
        }
    }
}
