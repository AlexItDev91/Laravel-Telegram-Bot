<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChannelConfigData
{
    public function __construct(
        public ?string $bot,
        public string|int|null $chatId,
        public string|int|null $messageThreadId = null,
        public string|int|null $directMessagesTopicId = null,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        return new self(
            bot: isset($config['bot']) ? (string) $config['bot'] : null,
            chatId: $config['chat_id'] ?? null,
            messageThreadId: $config['message_thread_id'] ?? null,
            directMessagesTopicId: $config['direct_messages_topic_id'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function chatDefaults(): array
    {
        return array_filter([
            'chat_id' => $this->chatId,
            'message_thread_id' => $this->messageThreadId,
            'direct_messages_topic_id' => $this->directMessagesTopicId,
        ], static fn (mixed $value): bool => $value !== null && $value !== '');
    }
}
