<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `close`.
 */
final readonly class CloseRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'close';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
