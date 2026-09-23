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
        /** @var list<InputRichBlock|RichBlock> */
        private array $blocks = [],
        /** @var list<InputRichMessageMedia> */
        private array $media = [],
    ) {
        $formats = (int) ($this->html !== null) + (int) ($this->markdown !== null) + (int) ($this->blocks !== []);

        if ($formats !== 1) {
            throw new InvalidArgumentException('Telegram input rich messages require exactly one of [html], [markdown], or [blocks].');
        }

        if ($this->blocks !== [] && $this->media !== []) {
            throw new InvalidArgumentException('Telegram rich message media references require HTML or Markdown content.');
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

    public static function blocks(InputRichBlock|RichBlock ...$blocks): self
    {
        return new self(blocks: array_values($blocks));
    }

    public function withMedia(InputRichMessageMedia ...$media): self
    {
        return new self($this->html, $this->markdown, $this->isRtl, $this->skipEntityDetection, $this->blocks, array_values([...$this->media, ...$media]));
    }

    public function rightToLeft(bool $isRtl = true): self
    {
        return new self($this->html, $this->markdown, $isRtl, $this->skipEntityDetection, $this->blocks, $this->media);
    }

    public function skipEntityDetection(bool $skip = true): self
    {
        return new self($this->html, $this->markdown, $this->isRtl, $skip, $this->blocks, $this->media);
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
            'blocks' => $this->blocks !== [] ? array_map(static fn (InputRichBlock|RichBlock $block): array => $block->toArray(), $this->blocks) : null,
            'media' => $this->media !== [] ? array_map(static fn (InputRichMessageMedia $item): array => $item->toArray(), $this->media) : null,
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
