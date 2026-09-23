<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;
use Stringable;

final readonly class InlineKeyboardMarkup implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  list<list<TelegramBotData|array<string, mixed>>>  $inlineKeyboard
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private array $inlineKeyboard,
        private array $extra = [],
        private ?bool $forceReply = null,
    ) {
        //
    }

    public static function singleButton(InlineKeyboardButton $button): self
    {
        return new self([[$button]]);
    }

    public static function make(): self
    {
        return new self([]);
    }

    /**
     * @param  list<TelegramBotData|array<string, mixed>>  $buttons
     */
    public static function fromButtons(array $buttons, int $columns = 1): self
    {
        $columns = self::positiveColumns($columns);

        $keyboard = self::make();

        foreach (array_chunk($buttons, $columns) as $row) {
            $keyboard = $keyboard->row(...$row);
        }

        return $keyboard;
    }

    /**
     * @param  TelegramBotData|array<string, mixed>  ...$buttons
     */
    public function row(TelegramBotData|array ...$buttons): self
    {
        if ($buttons === []) {
            throw new InvalidArgumentException('Telegram inline keyboard rows must contain at least one button.');
        }

        return new self([
            ...$this->inlineKeyboard,
            array_values($buttons),
        ], $this->extra, $this->forceReply);
    }

    /**
     * @param  TelegramBotData|array<string, mixed>  $button
     */
    public function button(TelegramBotData|array $button): self
    {
        return $this->row($button);
    }

    public function callback(string $text, string|Stringable $callbackData): self
    {
        return $this->button(InlineKeyboardButton::callback($text, $callbackData));
    }

    public function url(string $text, string $url): self
    {
        return $this->button(InlineKeyboardButton::url($text, $url));
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public function extra(array $extra): self
    {
        return new self($this->inlineKeyboard, array_merge($this->extra, $extra), $this->forceReply);
    }

    public function forceReply(bool $enabled = true): self
    {
        return new self($this->inlineKeyboard, $this->extra, $enabled);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'inline_keyboard' => $this->inlineKeyboard,
            'force_reply' => $this->forceReply,
        ], $this->extra, ['inline_keyboard']);
    }

    /**
     * @return int<1, max>
     */
    private static function positiveColumns(int $columns): int
    {
        if ($columns <= 0) {
            throw new InvalidArgumentException('Telegram inline keyboard column count must be greater than zero.');
        }

        return $columns;
    }
}
