<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getManagedBotToken`.
 */
final readonly class GetManagedBotTokenRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getManagedBotToken';

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
