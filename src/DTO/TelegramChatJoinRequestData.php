<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatJoinRequestData implements TelegramBotData
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

    public function userChatId(): int|string|null
    {
        $value = $this->payload['user_chat_id'] ?? null;

        return is_int($value) || is_string($value) ? $value : null;
    }

    public function date(): ?int
    {
        $date = $this->payload['date'] ?? null;

        return is_int($date) ? $date : null;
    }

    public function bio(): ?string
    {
        $bio = $this->payload['bio'] ?? null;

        return is_string($bio) ? $bio : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function inviteLink(): ?array
    {
        $inviteLink = $this->payload['invite_link'] ?? null;

        return is_array($inviteLink) ? $inviteLink : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
