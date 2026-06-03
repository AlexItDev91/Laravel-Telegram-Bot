<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Notifications;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

class TelegramNotificationMessage
{
    private string|TelegramBotApiMethod $method;

    /**
     * @var array<string, mixed>|TelegramBotRequestData
     */
    private array|TelegramBotRequestData $parameters;

    private ?string $botName = null;

    private ?string $channelName = null;

    /**
     * @param  array<string, mixed>|TelegramBotRequestData  $parameters
     */
    public function __construct(
        string|TelegramBotApiMethod $method = TelegramBotApiMethod::sendMessage,
        array|TelegramBotRequestData $parameters = [],
    ) {
        $this->method = $method;
        $this->parameters = $parameters;
    }

    public static function text(string $text): self
    {
        return new self(TelegramBotApiMethod::sendMessage, ['text' => $text]);
    }

    /**
     * @param  array<string, mixed>|TelegramBotRequestData  $parameters
     */
    public static function forMethod(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): self
    {
        return new self($method, $parameters);
    }

    public function bot(string $name): self
    {
        $this->botName = $name;

        return $this;
    }

    public function channel(string $name): self
    {
        $this->channelName = $name;

        return $this;
    }

    public function to(int|string $chatId): self
    {
        return $this->with(['chat_id' => $chatId]);
    }

    public function thread(int|string $messageThreadId): self
    {
        return $this->with(['message_thread_id' => $messageThreadId]);
    }

    public function directMessagesTopic(int|string $directMessagesTopicId): self
    {
        return $this->with(['direct_messages_topic_id' => $directMessagesTopicId]);
    }

    public function parseMode(string|TelegramParseMode $parseMode): self
    {
        return $this->with(['parse_mode' => $parseMode instanceof TelegramParseMode ? $parseMode->value : $parseMode]);
    }

    public function disableNotification(bool $disableNotification = true): self
    {
        return $this->with(['disable_notification' => $disableNotification]);
    }

    public function protectContent(bool $protectContent = true): self
    {
        return $this->with(['protect_content' => $protectContent]);
    }

    /**
     * @param  array<string, mixed>  $replyMarkup
     */
    public function replyMarkup(array $replyMarkup): self
    {
        return $this->with(['reply_markup' => $replyMarkup]);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function with(array $parameters): self
    {
        $this->parameters = array_merge($this->parameters(), $parameters);

        return $this;
    }

    public function methodName(string|TelegramBotApiMethod $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function method(): string|TelegramBotApiMethod
    {
        return $this->method;
    }

    /**
     * @return array<string, mixed>|TelegramBotRequestData
     */
    public function payload(): array|TelegramBotRequestData
    {
        return $this->parameters;
    }

    /**
     * @return array<string, mixed>
     */
    public function parameters(): array
    {
        return $this->parameters instanceof TelegramBotRequestData
            ? $this->parameters->toArray()
            : $this->parameters;
    }

    public function botName(): ?string
    {
        return $this->botName;
    }

    public function channelName(): ?string
    {
        return $this->channelName;
    }
}
