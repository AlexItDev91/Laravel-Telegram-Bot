<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getManagedBotAccessSettings`.
 */
final readonly class GetManagedBotAccessSettingsRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getManagedBotAccessSettings';

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
