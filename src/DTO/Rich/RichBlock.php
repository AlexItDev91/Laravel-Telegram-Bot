<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;

/**
 * @phpstan-type RichTextValue string|TelegramBotData|array<int|string, mixed>
 */
final readonly class RichBlock implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(private array $payload)
    {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self($payload);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function paragraph(string|TelegramBotData|array $text): self
    {
        return self::withText('paragraph', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function heading(string|TelegramBotData|array $text, int $size = 1): self
    {
        if ($size < 1 || $size > 6) {
            throw new InvalidArgumentException('Telegram rich block heading size must be between 1 and 6.');
        }

        return self::withText('heading', $text, ['size' => $size]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function pre(string|TelegramBotData|array $text, ?string $language = null): self
    {
        return self::withText('pre', $text, array_filter([
            'language' => $language,
        ], static fn (mixed $value): bool => $value !== null));
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function footer(string|TelegramBotData|array $text): self
    {
        return self::withText('footer', $text);
    }

    public static function divider(): self
    {
        return new self(['type' => 'divider']);
    }

    public static function math(string $expression): self
    {
        self::assertFilled('expression', $expression);

        return new self([
            'type' => 'mathematical_expression',
            'expression' => $expression,
        ]);
    }

    public static function anchor(string $name): self
    {
        self::assertFilled('name', $name);

        return new self([
            'type' => 'anchor',
            'name' => $name,
        ]);
    }

    /**
     * @param  list<TelegramBotData|array<string, mixed>>  $blocks
     * @param  RichTextValue|null  $credit
     */
    public static function blockquote(array $blocks, string|TelegramBotData|array|null $credit = null): self
    {
        self::assertNonEmptyList('blocks', $blocks);

        return new self(array_filter([
            'type' => 'blockquote',
            'blocks' => self::blockList($blocks),
            'credit' => $credit !== null ? self::richTextValue($credit) : null,
        ], static fn (mixed $value): bool => $value !== null));
    }

    /**
     * @param  RichTextValue  $text
     * @param  RichTextValue|null  $credit
     */
    public static function pullquote(string|TelegramBotData|array $text, string|TelegramBotData|array|null $credit = null): self
    {
        return self::withText('pullquote', $text, array_filter([
            'credit' => $credit !== null ? self::richTextValue($credit) : null,
        ], static fn (mixed $value): bool => $value !== null));
    }

    /**
     * @param  RichTextValue  $summary
     * @param  list<TelegramBotData|array<string, mixed>>  $blocks
     */
    public static function details(string|TelegramBotData|array $summary, array $blocks, ?bool $isOpen = null): self
    {
        self::assertNonEmptyList('blocks', $blocks);

        return new self(array_filter([
            'type' => 'details',
            'summary' => self::richTextValue($summary),
            'blocks' => self::blockList($blocks),
            'is_open' => $isOpen,
        ], static fn (mixed $value): bool => $value !== null));
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function thinking(string|TelegramBotData|array $text): self
    {
        return self::withText('thinking', $text);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @param  RichTextValue  $text
     * @param  array<string, mixed>  $extra
     */
    private static function withText(string $type, string|TelegramBotData|array $text, array $extra = []): self
    {
        return new self(array_merge([
            'type' => $type,
            'text' => self::richTextValue($text),
        ], $extra));
    }

    /**
     * @param  RichTextValue  $text
     * @return string|array<int|string, mixed>
     */
    private static function richTextValue(string|TelegramBotData|array $text): string|array
    {
        if ($text instanceof TelegramBotData) {
            return $text->toArray();
        }

        if (is_string($text)) {
            self::assertFilled('text', $text);

            return $text;
        }

        return array_map(
            static fn (mixed $value): mixed => $value instanceof TelegramBotData ? $value->toArray() : $value,
            $text,
        );
    }

    /**
     * @param  list<TelegramBotData|array<string, mixed>>  $blocks
     * @return list<array<string, mixed>>
     */
    private static function blockList(array $blocks): array
    {
        return array_map(
            static fn (TelegramBotData|array $block): array => $block instanceof TelegramBotData ? $block->toArray() : $block,
            $blocks,
        );
    }

    /**
     * @param  list<mixed>  $values
     */
    private static function assertNonEmptyList(string $field, array $values): void
    {
        if ($values === []) {
            throw new InvalidArgumentException("Telegram rich block field [$field] must not be empty.");
        }
    }

    private static function assertFilled(string $field, string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Telegram rich block field [$field] must not be empty.");
        }
    }
}
