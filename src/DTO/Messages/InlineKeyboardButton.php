<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class InlineKeyboardButton implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $webApp
     * @param  TelegramBotData|array<string, mixed>|null  $loginUrl
     * @param  TelegramBotData|array<string, mixed>|null  $switchInlineQueryChosenChat
     * @param  TelegramBotData|array<string, mixed>|null  $copyText
     * @param  TelegramBotData|array<string, mixed>|null  $callbackGame
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string $text,
        private ?string $url = null,
        private ?string $callbackData = null,
        private TelegramBotData|array|null $webApp = null,
        private TelegramBotData|array|null $loginUrl = null,
        private ?string $switchInlineQuery = null,
        private ?string $switchInlineQueryCurrentChat = null,
        private TelegramBotData|array|null $switchInlineQueryChosenChat = null,
        private TelegramBotData|array|null $copyText = null,
        private TelegramBotData|array|null $callbackGame = null,
        private ?bool $pay = null,
        private array $extra = [],
    ) {
        //
    }

    public static function callback(string $text, string $callbackData): self
    {
        return new self($text, callbackData: $callbackData);
    }

    public static function url(string $text, string $url): self
    {
        return new self($text, url: $url);
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload([
            'text' => $this->text,
            'url' => $this->url,
            'callback_data' => $this->callbackData,
            'web_app' => $this->webApp,
            'login_url' => $this->loginUrl,
            'switch_inline_query' => $this->switchInlineQuery,
            'switch_inline_query_current_chat' => $this->switchInlineQueryCurrentChat,
            'switch_inline_query_chosen_chat' => $this->switchInlineQueryChosenChat,
            'copy_text' => $this->copyText,
            'callback_game' => $this->callbackGame,
            'pay' => $this->pay,
        ], $this->extra, ['text']);
    }
}
