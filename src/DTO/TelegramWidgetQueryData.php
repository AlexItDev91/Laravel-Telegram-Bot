<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramChatType;

final readonly class TelegramWidgetQueryData extends TelegramObjectData
{
    public function id(): ?string
    {
        return $this->string('id');
    }

    public function from(): ?TelegramUserData
    {
        $value = $this->object('from');

        return $value !== null ? TelegramUserData::fromPayload($value) : null;
    }

    public function chat(): ?TelegramChatData
    {
        $value = $this->object('chat');

        return $value !== null ? TelegramChatData::fromPayload($value) : null;
    }

    public function messageId(): ?int
    {
        return $this->int('message_id');
    }

    public function inlineMessageId(): ?string
    {
        return $this->string('inline_message_id');
    }

    public function chatInstance(): ?string
    {
        return $this->string('chat_instance');
    }

    public function chatType(): ?string
    {
        return $this->string('chat_type');
    }

    public function chatTypeEnum(): ?TelegramChatType
    {
        $type = $this->chatType();

        return $type !== null ? TelegramChatType::tryFrom($type) : null;
    }

    public function type(): ?TelegramWidgetQueryTypeData
    {
        $value = $this->object('type');

        return $value !== null ? TelegramWidgetQueryTypeData::fromPayload($value) : null;
    }

    public function data(): ?string
    {
        return $this->string('data');
    }

    public function widgetId(): ?string
    {
        return $this->string('widget_id');
    }
}
