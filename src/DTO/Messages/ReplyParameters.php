<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

final readonly class ReplyParameters implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, array<string, mixed>>|null  $quoteEntities
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private int|string $messageId,
        private int|string|null $chatId = null,
        private ?bool $allowSendingWithoutReply = null,
        private ?string $quote = null,
        private string|TelegramParseMode|null $quoteParseMode = null,
        private ?array $quoteEntities = null,
        private ?int $quotePosition = null,
        private ?string $pollOptionId = null,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload([
            'message_id' => $this->messageId,
            'chat_id' => $this->chatId,
            'allow_sending_without_reply' => $this->allowSendingWithoutReply,
            'quote' => $this->quote,
            'quote_parse_mode' => $this->quoteParseMode,
            'quote_entities' => $this->quoteEntities,
            'quote_position' => $this->quotePosition,
            'poll_option_id' => $this->pollOptionId,
        ], $this->extra, ['message_id']);
    }
}
