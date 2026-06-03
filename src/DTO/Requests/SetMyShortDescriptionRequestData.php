<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setMyShortDescription`.
 */
final readonly class SetMyShortDescriptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setMyShortDescription';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?string $shortDescription = null,
        ?string $languageCode = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'short_description' => $shortDescription,
            'language_code' => $languageCode,
        ], $extra)));
    }
}
