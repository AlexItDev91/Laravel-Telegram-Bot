<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatFullInfoData extends TelegramObjectData
{
    public function id(): int|string|null
    {
        return $this->identifier('id');
    }

    public function type(): ?string
    {
        return $this->string('type');
    }

    public function title(): ?string
    {
        return $this->string('title');
    }

    public function username(): ?string
    {
        return $this->string('username');
    }

    public function firstName(): ?string
    {
        return $this->string('first_name');
    }

    public function lastName(): ?string
    {
        return $this->string('last_name');
    }

    public function isForum(): ?bool
    {
        return $this->bool('is_forum');
    }

    public function accentColorId(): ?int
    {
        return $this->int('accent_color_id');
    }

    public function maxReactionCount(): ?int
    {
        return $this->int('max_reaction_count');
    }

    public function bio(): ?string
    {
        return $this->string('bio');
    }

    public function description(): ?string
    {
        return $this->string('description');
    }

    public function inviteLink(): ?string
    {
        return $this->string('invite_link');
    }

    public function pinnedMessage(): ?TelegramMessageData
    {
        $message = $this->object('pinned_message');

        return $message !== null ? TelegramMessageData::fromPayload($message) : null;
    }

    public function guardBot(): ?TelegramUserData
    {
        $bot = $this->object('guard_bot');

        return $bot !== null ? TelegramUserData::fromPayload($bot) : null;
    }
}
