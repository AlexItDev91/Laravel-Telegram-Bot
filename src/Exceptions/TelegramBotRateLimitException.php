<?php

namespace AlexItDev91\LaravelTelegramBot\Exceptions;

final class TelegramBotRateLimitException extends TelegramBotException
{
    public function __construct(
        string $message,
        private readonly int $availableIn,
    ) {
        parent::__construct($message);
    }

    public function availableIn(): int
    {
        return $this->availableIn;
    }
}
