<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `logOut`.
 */
final readonly class LogOutRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'logOut';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
