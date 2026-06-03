<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setPassportDataErrors`.
 */
final readonly class SetPassportDataErrorsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setPassportDataErrors';

    /**
     * @param  array<string|int, mixed>  $errors
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        array $errors,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'errors' => $errors,
        ], $extra)));
    }
}
