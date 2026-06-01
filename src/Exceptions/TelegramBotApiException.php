<?php

namespace Aptenova\TelegramBot\Exceptions;

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
}
