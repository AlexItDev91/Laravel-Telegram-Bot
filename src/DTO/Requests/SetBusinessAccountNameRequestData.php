<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setBusinessAccountName`.
 */
final readonly class SetBusinessAccountNameRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setBusinessAccountName';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        string $firstName,
        ?string $lastName = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ], $extra)));
    }
}
