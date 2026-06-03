<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
final readonly class TelegramMessageData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(
        private array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }

    public function messageId(): ?int
    {
        return $this->intAt('message_id');
    }

    public function messageThreadId(): ?int
    {
        return $this->intAt('message_thread_id');
    }

    public function date(): ?int
    {
        return $this->intAt('date');
    }

    public function text(): ?string
    {
        return $this->stringAt('text');
    }

    public function caption(): ?string
    {
        return $this->stringAt('caption');
    }

    public function guestQueryId(): ?string
    {
        return $this->stringAt('guest_query_id');
    }

    public function chat(): ?TelegramChatData
    {
        $chat = $this->payload['chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    public function from(): ?TelegramUserData
    {
        $from = $this->payload['from'] ?? null;

        return is_array($from) ? TelegramUserData::fromPayload($from) : null;
    }

    public function senderChat(): ?TelegramChatData
    {
        $senderChat = $this->payload['sender_chat'] ?? null;

        return is_array($senderChat) ? TelegramChatData::fromPayload($senderChat) : null;
    }

    public function replyToMessage(): ?self
    {
        $message = $this->payload['reply_to_message'] ?? null;

        return is_array($message) ? self::fromPayload($message) : null;
    }

    public function guestBotCallerUser(): ?TelegramUserData
    {
        $user = $this->payload['guest_bot_caller_user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    public function guestBotCallerChat(): ?TelegramChatData
    {
        $chat = $this->payload['guest_bot_caller_chat'] ?? null;

        return is_array($chat) ? TelegramChatData::fromPayload($chat) : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function photo(): array
    {
        return $this->listOfArraysAt('photo');
    }

    /**
     * @return list<TelegramPhotoSizeData>
     */
    public function photoData(): array
    {
        return array_map(
            static fn (array $photo): TelegramPhotoSizeData => TelegramPhotoSizeData::fromPayload($photo),
            $this->photo(),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function document(): ?array
    {
        return $this->arrayAt('document');
    }

    public function documentData(): ?TelegramDocumentData
    {
        $document = $this->document();

        return $document !== null ? TelegramDocumentData::fromPayload($document) : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function entities(): array
    {
        return $this->listOfArraysAt('entities');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function entitiesData(): array
    {
        return array_map(
            static fn (array $entity): TelegramMessageEntityData => TelegramMessageEntityData::fromPayload($entity),
            $this->entities(),
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function captionEntities(): array
    {
        return $this->listOfArraysAt('caption_entities');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function captionEntitiesData(): array
    {
        return array_map(
            static fn (array $entity): TelegramMessageEntityData => TelegramMessageEntityData::fromPayload($entity),
            $this->captionEntities(),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function successfulPayment(): ?array
    {
        return $this->arrayAt('successful_payment');
    }

    public function successfulPaymentData(): ?TelegramSuccessfulPaymentData
    {
        $successfulPayment = $this->successfulPayment();

        return $successfulPayment !== null ? TelegramSuccessfulPaymentData::fromPayload($successfulPayment) : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function passportData(): ?array
    {
        return $this->arrayAt('passport_data');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function game(): ?array
    {
        return $this->arrayAt('game');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function livePhoto(): ?array
    {
        return $this->arrayAt('live_photo');
    }

    public function isTopicMessage(): ?bool
    {
        return $this->boolAt('is_topic_message');
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) ? $value : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listOfArraysAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_array($item)));
    }
}
