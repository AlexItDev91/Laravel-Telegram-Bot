<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Games;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class InlineQueryResultGame implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string $id,
        private string $gameShortName,
        private TelegramBotData|array|null $replyMarkup = null,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload([
            'type' => 'game',
            'id' => $this->id,
            'game_short_name' => $this->gameShortName,
            'reply_markup' => $this->replyMarkup,
        ], $this->extra, ['type', 'id', 'game_short_name']);
    }
}
