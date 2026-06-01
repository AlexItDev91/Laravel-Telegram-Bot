<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

interface TelegramBotData
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
