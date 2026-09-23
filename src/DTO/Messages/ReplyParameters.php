<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use InvalidArgumentException;

final readonly class ReplyParameters implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, array<string, mixed>>|null  $quoteEntities
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private int|string|null $messageId = null,
        private int|string|null $chatId = null,
        private ?bool $allowSendingWithoutReply = null,
        private ?string $quote = null,
        private string|TelegramParseMode|null $quoteParseMode = null,
        private ?array $quoteEntities = null,
        private ?int $quotePosition = null,
        private ?string $pollOptionId = null,
        private array $extra = [],
        private int|string|null $ephemeralMessageId = null,
    ) {
        if (($this->messageId === null) === ($this->ephemeralMessageId === null)) {
            throw new InvalidArgumentException('Telegram reply requires exactly one of [message_id] or [ephemeral_message_id].');
        }
    }

    public static function forEphemeralMessage(int|string $ephemeralMessageId): self
    {
        return new self(ephemeralMessageId: $ephemeralMessageId);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'message_id' => $this->messageId,
            'ephemeral_message_id' => $this->ephemeralMessageId,
            'chat_id' => $this->chatId,
            'allow_sending_without_reply' => $this->allowSendingWithoutReply,
            'quote' => $this->quote,
            'quote_parse_mode' => $this->quoteParseMode,
            'quote_entities' => $this->quoteEntities,
            'quote_position' => $this->quotePosition,
            'poll_option_id' => $this->pollOptionId,
        ], $this->extra, [$this->messageId !== null ? 'message_id' : 'ephemeral_message_id']);
    }
}
