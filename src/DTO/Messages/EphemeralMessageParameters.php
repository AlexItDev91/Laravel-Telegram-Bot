<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;
use Override;

final readonly class EphemeralMessageParameters implements TelegramBotData
{
    private function __construct(
        private int|string $receiverUserId,
        private ?string $callbackQueryId = null,
        private ?bool $replaceCallbackQueryMessage = null,
    ) {
        if ($receiverUserId === '' || $receiverUserId === 0) {
            throw new InvalidArgumentException('Telegram ephemeral receiver user ID must not be empty.');
        }
    }

    public static function forUser(int|string $receiverUserId): self
    {
        return new self($receiverUserId);
    }

    public function fromCallbackQuery(string $callbackQueryId): self
    {
        if (trim($callbackQueryId) === '') {
            throw new InvalidArgumentException('Telegram callback query ID must not be empty.');
        }

        return new self($this->receiverUserId, $callbackQueryId, $this->replaceCallbackQueryMessage);
    }

    public function replaceCallbackQueryMessage(bool $replace = true): self
    {
        return new self($this->receiverUserId, $this->callbackQueryId, $replace);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'receiver_user_id' => $this->receiverUserId,
            'callback_query_id' => $this->callbackQueryId,
            'replace_callback_query_message' => $this->replaceCallbackQueryMessage,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
