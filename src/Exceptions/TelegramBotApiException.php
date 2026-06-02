<?php

namespace AlexItDev91\LaravelTelegramBot\Exceptions;

class TelegramBotApiException extends TelegramBotException
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public function __construct(
        string $message,
        private readonly ?int $telegramErrorCode = null,
        private readonly array $parameters = [],
    ) {
        parent::__construct($message, $telegramErrorCode ?? 0);
    }

    public function telegramErrorCode(): ?int
    {
        return $this->telegramErrorCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function retryAfter(): ?int
    {
        return $this->integerParameter('retry_after');
    }

    public function migrateToChatId(): int|string|null
    {
        $chatId = $this->parameters['migrate_to_chat_id'] ?? null;

        return is_int($chatId) || is_string($chatId) ? $chatId : null;
    }

    private function integerParameter(string $key): ?int
    {
        $value = $this->parameters[$key] ?? null;

        return is_int($value) ? $value : null;
    }
}
