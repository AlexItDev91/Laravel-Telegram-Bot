<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setMyName`.
 */
final readonly class SetMyNameRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setMyName';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?string $name = null,
        ?string $languageCode = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'name' => $name,
            'language_code' => $languageCode,
        ], $extra)));
    }
}
