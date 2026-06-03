<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setBusinessAccountBio`.
 */
final readonly class SetBusinessAccountBioRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setBusinessAccountBio';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        ?string $bio = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'bio' => $bio,
        ], $extra)));
    }
}
