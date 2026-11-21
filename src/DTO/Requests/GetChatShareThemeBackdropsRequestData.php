<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getChatShareThemeBackdrops`.
 */
final readonly class GetChatShareThemeBackdropsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getChatShareThemeBackdrops';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
