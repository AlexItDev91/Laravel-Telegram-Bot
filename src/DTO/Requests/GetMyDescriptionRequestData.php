<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getMyDescription`.
 */
final readonly class GetMyDescriptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getMyDescription';

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
