<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setBusinessAccountUsername`.
 */
final readonly class SetBusinessAccountUsernameRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setBusinessAccountUsername';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        ?string $username = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'username' => $username,
        ], $extra)));
    }
}
