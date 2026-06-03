<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

interface TelegramBotMethodRequest
{
    public function method(): string;

    public function validatesRequiredParameters(): bool;

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    public function schema(): array;

    /**
     * @return list<string>
     */
    public function requiredParameters(): array;
}
