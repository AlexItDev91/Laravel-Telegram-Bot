<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getMyName`.
 */
final readonly class GetMyNameRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getMyName';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?string $languageCode = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'language_code' => $languageCode,
        ], $extra)));
    }
}
