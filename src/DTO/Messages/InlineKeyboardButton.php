<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;
use Override;
use Stringable;

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
        private string|Stringable|null $callbackData = null,
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
        self::assertFilledString('text', $text);

        if ($callbackData !== null) {
            self::assertCallbackData((string) $callbackData);
        }
    }

    public static function callback(string $text, string|Stringable $callbackData): self
    {
        return new self($text, callbackData: $callbackData);
    }

    public static function url(string $text, string $url): self
    {
        return new self($text, url: $url);
    }

    /**
     * @param  TelegramBotData|array<string, mixed>  $webApp
     */
    public static function webApp(string $text, TelegramBotData|array $webApp): self
    {
        return new self($text, webApp: $webApp);
    }

    /**
     * @param  TelegramBotData|array<string, mixed>  $loginUrl
     */
    public static function loginUrl(string $text, TelegramBotData|array $loginUrl): self
    {
        return new self($text, loginUrl: $loginUrl);
    }

    public static function switchInlineQuery(string $text, string $query = ''): self
    {
        return new self($text, switchInlineQuery: $query);
    }

    public static function switchInlineQueryCurrentChat(string $text, string $query = ''): self
    {
        return new self($text, switchInlineQueryCurrentChat: $query);
    }

    /**
     * @param  TelegramBotData|array<string, mixed>  $switchInlineQueryChosenChat
     */
    public static function switchInlineQueryChosenChat(string $text, TelegramBotData|array $switchInlineQueryChosenChat): self
    {
        return new self($text, switchInlineQueryChosenChat: $switchInlineQueryChosenChat);
    }

    public static function copyText(string $text, string $copyText): self
    {
        return new self($text, copyText: ['text' => $copyText]);
    }

    public static function pay(string $text): self
    {
        return new self($text, pay: true);
    }

    /**
     * @param  TelegramBotData|array<string, mixed>  $callbackGame
     */
    public static function callbackGame(string $text, TelegramBotData|array $callbackGame): self
    {
        return new self($text, callbackGame: $callbackGame);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'text' => $this->text,
            'url' => $this->url,
            'callback_data' => $this->callbackData !== null ? (string) $this->callbackData : null,
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

    private static function assertFilledString(string $field, string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Telegram inline keyboard button field [$field] must not be empty.");
        }
    }

    private static function assertCallbackData(string $callbackData): void
    {
        $bytes = strlen($callbackData);

        if ($bytes < 1 || $bytes > 64) {
            throw new InvalidArgumentException('Telegram inline keyboard callback_data must be between 1 and 64 bytes.');
        }
    }
}
