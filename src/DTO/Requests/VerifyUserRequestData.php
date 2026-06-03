<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `verifyUser`.
 */
final readonly class VerifyUserRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'verifyUser';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        ?string $customDescription = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'custom_description' => $customDescription,
        ], $extra)));
    }
}
