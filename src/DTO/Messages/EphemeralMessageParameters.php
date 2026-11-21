<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;
use Override;

final readonly class EphemeralMessageParameters implements TelegramBotData
{
    private function __construct(
        private int|string|null $receiverUserId,
        private ?string $callbackQueryId = null,
        private ?bool $replaceCallbackQueryMessage = null,
    ) {
        if ($receiverUserId === '' || $receiverUserId === 0) {
            throw new InvalidArgumentException('Telegram ephemeral receiver user ID must not be empty.');
        }

        if ($callbackQueryId !== null && trim($callbackQueryId) === '') {
            throw new InvalidArgumentException('Telegram callback query ID must not be empty.');
        }
    }

    public static function forUser(int|string $receiverUserId): self
    {
        return new self($receiverUserId);
    }

    public static function forCallbackQuery(string $callbackQueryId): self
    {
        return new self(null, $callbackQueryId);
    }

    public function fromCallbackQuery(string $callbackQueryId): self
    {
        return new self($this->receiverUserId, $callbackQueryId, $this->replaceCallbackQueryMessage);
    }

    /** @internal */
    public static function isCallbackReply(mixed $parameters): bool
    {
        if ($parameters instanceof TelegramBotData) {
            $parameters = $parameters->toArray();
        }

        if (is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        }

        if (! is_array($parameters) || ($parameters['receiver_user_id'] ?? null) !== null) {
            return false;
        }

        $callbackQueryId = $parameters['callback_query_id'] ?? null;

        return is_string($callbackQueryId) && trim($callbackQueryId) !== '';
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
