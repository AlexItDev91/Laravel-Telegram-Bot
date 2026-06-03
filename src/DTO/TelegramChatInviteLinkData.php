<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatInviteLinkData extends TelegramObjectData
{
    public function inviteLink(): ?string
    {
        return $this->string('invite_link');
    }

    public function creator(): ?TelegramUserData
    {
        $creator = $this->object('creator');

        return $creator !== null ? TelegramUserData::fromPayload($creator) : null;
    }

    public function createsJoinRequest(): ?bool
    {
        return $this->bool('creates_join_request');
    }

    public function isPrimary(): ?bool
    {
        return $this->bool('is_primary');
    }

    public function isRevoked(): ?bool
    {
        return $this->bool('is_revoked');
    }

    public function name(): ?string
    {
        return $this->string('name');
    }

    public function expireDate(): ?int
    {
        return $this->int('expire_date');
    }

    public function memberLimit(): ?int
    {
        return $this->int('member_limit');
    }

    public function pendingJoinRequestCount(): ?int
    {
        return $this->int('pending_join_request_count');
    }
}
