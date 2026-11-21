<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramExternalReplyInfoData extends TelegramObjectData
{
    public function chat(): ?TelegramChatData
    {
        $value = $this->object('chat');

        return $value !== null ? TelegramChatData::fromPayload($value) : null;
    }

    public function messageId(): ?int
    {
        return $this->int('message_id');
    }

    public function widget(): ?TelegramWidgetData
    {
        $value = $this->object('widget');

        return $value !== null ? TelegramWidgetData::fromPayload($value) : null;
    }
}
