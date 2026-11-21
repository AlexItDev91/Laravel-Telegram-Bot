<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramSharedWidgetQueryData extends TelegramObjectData
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
