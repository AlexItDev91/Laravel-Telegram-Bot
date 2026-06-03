<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

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
    ) {
        //
    }

    public static function singleButton(InlineKeyboardButton $button): self
    {
        return new self([[$button]]);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'inline_keyboard' => $this->inlineKeyboard,
        ], $this->extra, ['inline_keyboard']);
    }
}
