<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramSentWebAppMessageData extends TelegramObjectData
{
    public function inlineMessageId(): ?string
    {
        return $this->string('inline_message_id');
    }
}
