<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Concerns;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

trait BuildsTelegramBotPayload
{
    /**
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private static function payload(array $parameters, array $extra = []): array
    {
        return TelegramBotRequestData::normalizeValue(
            TelegramBotRequestData::withoutNullValues(array_merge($parameters, $extra)),
        );
    }
}
