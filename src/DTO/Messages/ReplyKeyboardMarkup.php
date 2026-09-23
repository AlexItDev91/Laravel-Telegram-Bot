<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;
use Override;

final readonly class ReplyKeyboardMarkup implements TelegramBotData
{
    /**
     * @param list<list<string|TelegramBotData>> $keyboard
     */
    private function __construct(
        private array $keyboard,
        private ?bool $forceReply = null,
        private ?bool $isPersistent = null,
        private ?bool $resizeKeyboard = null,
        private ?bool $oneTimeKeyboard = null,
        private ?string $inputFieldPlaceholder = null,
        private ?bool $selective = null,
    ) {
        //
    }

    public static function make(): self
    {
        return new self([]);
    }

    public function row(string|TelegramBotData ...$buttons): self
    {
        if ($buttons === []) {
            throw new InvalidArgumentException('Telegram reply keyboard rows must contain at least one button.');
        }

        return new self(
            [...$this->keyboard, array_values($buttons)],
            $this->forceReply,
            $this->isPersistent,
            $this->resizeKeyboard,
            $this->oneTimeKeyboard,
            $this->inputFieldPlaceholder,
            $this->selective,
        );
    }

    public function forceReply(bool $enabled = true): self
    {
        return new self($this->keyboard, $enabled, $this->isPersistent, $this->resizeKeyboard, $this->oneTimeKeyboard, $this->inputFieldPlaceholder, $this->selective);
    }

    public function persistent(bool $enabled = true): self
    {
        return new self($this->keyboard, $this->forceReply, $enabled, $this->resizeKeyboard, $this->oneTimeKeyboard, $this->inputFieldPlaceholder, $this->selective);
    }

    public function resize(bool $enabled = true): self
    {
        return new self($this->keyboard, $this->forceReply, $this->isPersistent, $enabled, $this->oneTimeKeyboard, $this->inputFieldPlaceholder, $this->selective);
    }

    public function oneTime(bool $enabled = true): self
    {
        return new self($this->keyboard, $this->forceReply, $this->isPersistent, $this->resizeKeyboard, $enabled, $this->inputFieldPlaceholder, $this->selective);
    }

    public function placeholder(string $placeholder): self
    {
        $length = preg_match_all('/./us', $placeholder);

        if ($length === false || $length < 1 || $length > 64) {
            throw new InvalidArgumentException('Telegram reply keyboard placeholder must contain 1-64 characters.');
        }

        return new self($this->keyboard, $this->forceReply, $this->isPersistent, $this->resizeKeyboard, $this->oneTimeKeyboard, $placeholder, $this->selective);
    }

    public function selective(bool $enabled = true): self
    {
        return new self($this->keyboard, $this->forceReply, $this->isPersistent, $this->resizeKeyboard, $this->oneTimeKeyboard, $this->inputFieldPlaceholder, $enabled);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'keyboard' => array_map(static fn (array $row): array => array_map(static fn (string|TelegramBotData $button): string|array => $button instanceof TelegramBotData ? $button->toArray() : $button, $row), $this->keyboard),
            'is_persistent' => $this->isPersistent,
            'resize_keyboard' => $this->resizeKeyboard,
            'one_time_keyboard' => $this->oneTimeKeyboard,
            'input_field_placeholder' => $this->inputFieldPlaceholder,
            'selective' => $this->selective,
            'force_reply' => $this->forceReply,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
