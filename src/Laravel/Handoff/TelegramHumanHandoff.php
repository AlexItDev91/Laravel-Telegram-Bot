<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Handoff;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationContext;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWorkflow;

final readonly class TelegramHumanHandoff
{
    public const string STATE = 'human_handoff';

    public const string CONTEXT_KEY = '_human_handoff';

    /**
     * @param  array<string, scalar|null>  $metadata
     */
    private function __construct(
        private string $reason,
        private int $openedAt,
        private ?string $userId,
        private ?string $username,
        private ?string $displayName,
        private ?string $chatId,
        private ?int $messageId,
        private ?int $messageThreadId,
        private array $metadata = [],
    ) {
        //
    }

    /**
     * @param  array<string, scalar|null>  $metadata
     */
    public static function make(
        string $reason,
        array $metadata = [],
        ?int $openedAt = null,
        int|string|null $userId = null,
        ?string $username = null,
        ?string $userName = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?int $messageThreadId = null,
    ): self {
        return new self(
            reason: $reason,
            openedAt: $openedAt ?? time(),
            userId: self::identifier($userId),
            username: self::filled($username),
            displayName: self::filled($userName),
            chatId: self::identifier($chatId),
            messageId: $messageId,
            messageThreadId: $messageThreadId,
            metadata: self::normalizeMetadata($metadata),
        );
    }

    /**
     * @param  array<string, scalar|null>  $metadata
     */
    public static function fromUpdate(
        TelegramWebhookUpdate $update,
        string $reason,
        array $metadata = [],
        ?int $openedAt = null,
    ): self {
        $user = $update->effectiveUser();
        $message = $update->effectiveMessage();

        return self::make(
            reason: $reason,
            metadata: $metadata,
            openedAt: $openedAt,
            userId: $user?->id(),
            username: $user?->username(),
            userName: self::resolveDisplayName($user?->firstName(), $user?->lastName()),
            chatId: $update->effectiveChat()?->id(),
            messageId: $message?->messageId(),
            messageThreadId: $message?->messageThreadId(),
        );
    }

    public static function fromContext(TelegramConversationContext $context): ?self
    {
        $data = $context->array(self::CONTEXT_KEY);

        if ($data === null || ! is_string($data['reason'] ?? null) || ! is_int($data['opened_at'] ?? null)) {
            return null;
        }

        return self::make(
            reason: $data['reason'],
            metadata: is_array($data['metadata'] ?? null) ? $data['metadata'] : [],
            openedAt: $data['opened_at'],
            userId: self::stringOrNull($data['user_id'] ?? null),
            username: self::stringOrNull($data['username'] ?? null),
            userName: self::stringOrNull($data['user_name'] ?? null),
            chatId: self::stringOrNull($data['chat_id'] ?? null),
            messageId: is_int($data['message_id'] ?? null) ? $data['message_id'] : null,
            messageThreadId: is_int($data['message_thread_id'] ?? null) ? $data['message_thread_id'] : null,
        );
    }

    public static function fromWorkflow(TelegramConversationWorkflow $workflow): ?self
    {
        return $workflow->is(self::STATE) ? self::fromContext($workflow->context()) : null;
    }

    public static function close(TelegramConversationWorkflow $workflow): void
    {
        $workflow->reset();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function open(TelegramConversationWorkflow $workflow, array $data = [], ?int $ttl = null): TelegramConversationData
    {
        return $workflow->start(self::STATE, $this->conversationData($data), $ttl);
    }

    public function toOperatorMessage(
        int|string $chatId,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
        string $title = 'Human handoff requested',
    ): SendMessageData {
        return new SendMessageData(
            chatId: $chatId,
            text: $this->operatorText($title),
            messageThreadId: $messageThreadId,
            directMessagesTopicId: $directMessagesTopicId,
        );
    }

    public function operatorText(string $title = 'Human handoff requested'): string
    {
        $lines = [
            $title,
            'Reason: '.$this->reason,
            'Opened at: '.gmdate(DATE_ATOM, $this->openedAt),
        ];

        foreach ([
            'User ID' => $this->userId,
            'Username' => $this->username !== null ? '@'.$this->username : null,
            'Name' => $this->displayName,
            'Source chat ID' => $this->chatId,
            'Source message ID' => $this->messageId,
            'Source message thread ID' => $this->messageThreadId,
        ] as $label => $value) {
            if ($value !== null && $value !== '') {
                $lines[] = $label.': '.(string) $value;
            }
        }

        foreach ($this->metadata as $key => $value) {
            if ($value !== null && $value !== '') {
                $lines[] = $key.': '.(string) $value;
            }
        }

        return implode("\n", $lines);
    }

    /**
     * @return array<string, mixed>
     */
    public function toContext(): array
    {
        return [
            self::CONTEXT_KEY => [
                'reason' => $this->reason,
                'opened_at' => $this->openedAt,
                'user_id' => $this->userId,
                'username' => $this->username,
                'user_name' => $this->displayName,
                'chat_id' => $this->chatId,
                'message_id' => $this->messageId,
                'message_thread_id' => $this->messageThreadId,
                'metadata' => $this->metadata,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function conversationData(array $data = []): array
    {
        return array_merge($data, $this->toContext());
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function openedAt(): int
    {
        return $this->openedAt;
    }

    public function userId(): ?string
    {
        return $this->userId;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    public function displayName(): ?string
    {
        return $this->displayName;
    }

    public function chatId(): ?string
    {
        return $this->chatId;
    }

    public function messageId(): ?int
    {
        return $this->messageId;
    }

    public function messageThreadId(): ?int
    {
        return $this->messageThreadId;
    }

    /**
     * @return array<string, scalar|null>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    private static function identifier(int|string|null $value): ?string
    {
        return $value === null ? null : (string) $value;
    }

    private static function stringOrNull(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }

    private static function filled(?string $value): ?string
    {
        $value = $value !== null ? trim($value) : null;

        return $value !== null && $value !== '' ? $value : null;
    }

    private static function resolveDisplayName(?string $firstName, ?string $lastName): ?string
    {
        return self::filled(trim(implode(' ', array_filter([$firstName, $lastName], is_string(...)))));
    }

    /**
     * @param  array<string, scalar|null>  $metadata
     * @return array<string, scalar|null>
     */
    private static function normalizeMetadata(array $metadata): array
    {
        $normalized = [];

        foreach ($metadata as $key => $value) {
            if ($key !== '' && (is_scalar($value) || $value === null)) {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }
}
