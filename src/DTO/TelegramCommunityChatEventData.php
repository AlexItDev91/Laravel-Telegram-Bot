<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramCommunityChatEventData extends TelegramObjectData
{
    public function community(): ?TelegramCommunityData
    {
        $community = $this->object('community');

        return $community !== null ? TelegramCommunityData::fromPayload($community) : null;
    }
}
