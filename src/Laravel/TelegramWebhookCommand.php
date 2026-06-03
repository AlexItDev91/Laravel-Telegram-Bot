<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;

final readonly class TelegramWebhookCommand
{
    private const PATTERN = '/^\/(\w{1,32})(?:@(\w{1,32}))?(?:\s+(.*))?$/s';

    public function __construct(
        private TelegramMessageData $message,
        private string $name,
        private string $arguments = '',
        private ?string $botUsername = null,
    ) {
        //
    }

    public static function fromUpdate(TelegramWebhookUpdate $update): ?self
    {
        $message = $update->effectiveMessage();
        $text = $message?->text();

        if ($message === null || $text === null || preg_match(self::PATTERN, trim($text), $matches) !== 1) {
            return null;
        }

        return new self(
            message: $message,
            name: strtolower($matches[1]),
            arguments: trim($matches[3] ?? ''),
            botUsername: isset($matches[2]) && $matches[2] !== '' ? strtolower($matches[2]) : null,
        );
    }

    public function message(): TelegramMessageData
    {
        return $this->message;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function arguments(): string
    {
        return $this->arguments;
    }

    public function botUsername(): ?string
    {
        return $this->botUsername;
    }

    /**
     * @return list<string>
     */
    public function argumentParts(): array
    {
        if ($this->arguments === '') {
            return [];
        }

        return preg_split('/\s+/', $this->arguments) ?: [];
    }

    public function isAddressedTo(?string $botUsername): bool
    {
        if ($this->botUsername === null || $botUsername === null || $botUsername === '') {
            return true;
        }

        return $this->botUsername === strtolower(ltrim($botUsername, '@'));
    }
}
