<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramRichBlockType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramRichButtonAlignment;
use InvalidArgumentException;
use Override;

final readonly class InputRichBlock implements TelegramBotData
{
    private function __construct(
        private TelegramRichBlockType $type,
        private ?string $text = null,
        /** @var list<RichMessageButton> */
        private array $buttons = [],
        private ?TelegramRichButtonAlignment $align = null,
    ) {
        //
    }

    public static function paragraph(string $text): self
    {
        if (trim($text) === '') {
            throw new InvalidArgumentException('Telegram rich paragraph text must not be empty.');
        }

        return new self(TelegramRichBlockType::Paragraph, $text);
    }

    public static function expandableBlockquote(string $text): self
    {
        if (trim($text) === '') {
            throw new InvalidArgumentException('Telegram expandable block quotation text must not be empty.');
        }

        return new self(TelegramRichBlockType::ExpandableBlockQuotation, $text);
    }

    public static function buttons(RichMessageButton ...$buttons): self
    {
        if (count($buttons) < 1 || count($buttons) > 8) {
            throw new InvalidArgumentException('Telegram rich button rows require 1-8 buttons.');
        }

        return new self(TelegramRichBlockType::Buttons, buttons: array_values($buttons));
    }

    public function aligned(TelegramRichButtonAlignment $alignment): self
    {
        if ($this->type !== TelegramRichBlockType::Buttons) {
            throw new InvalidArgumentException('Telegram rich button alignment requires a button block.');
        }

        return new self($this->type, $this->text, $this->buttons, $alignment);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type->value,
            'text' => $this->text,
            'buttons' => $this->buttons !== [] ? array_map(static fn (RichMessageButton $button): array => $button->toArray(), $this->buttons) : null,
            'align' => $this->align?->value,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
