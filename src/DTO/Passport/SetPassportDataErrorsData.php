<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Passport;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class SetPassportDataErrorsData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, PassportElementError|array<string, mixed>>  $errors
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $userId,
        array $errors,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'user_id' => $userId,
            'errors' => $errors,
        ], $extra));
    }
}
