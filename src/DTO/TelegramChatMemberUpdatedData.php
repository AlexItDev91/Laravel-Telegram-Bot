<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatMemberUpdatedData implements TelegramBotData
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

    public function date(): ?int
    {
        $date = $this->payload['date'] ?? null;

        return is_int($date) ? $date : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function oldChatMember(): ?array
    {
        return $this->arrayAt('old_chat_member');
    }

    public function oldChatMemberData(): ?TelegramChatMemberData
    {
        $oldChatMember = $this->oldChatMember();

        return $oldChatMember !== null ? TelegramChatMemberData::fromPayload($oldChatMember) : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function newChatMember(): ?array
    {
        return $this->arrayAt('new_chat_member');
    }

    public function newChatMemberData(): ?TelegramChatMemberData
    {
        $newChatMember = $this->newChatMember();

        return $newChatMember !== null ? TelegramChatMemberData::fromPayload($newChatMember) : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function inviteLink(): ?array
    {
        return $this->arrayAt('invite_link');
    }

    public function viaJoinRequest(): ?bool
    {
        return $this->boolAt('via_join_request');
    }

    public function viaChatFolderInviteLink(): ?bool
    {
        return $this->boolAt('via_chat_folder_invite_link');
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) ? $value : null;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }
}
