<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Passport;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class PassportScope implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, string|PassportScopeElement|array<string, mixed>>  $data
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private array $data,
        private int $version = 1,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return self::payload([
            'v' => $this->version,
            'data' => $this->data,
        ], $this->extra);
    }
}
