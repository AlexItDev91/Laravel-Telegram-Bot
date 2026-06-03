<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `removeUserVerification`.
 */
final readonly class RemoveUserVerificationRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'removeUserVerification';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
        ], $extra)));
    }
}
