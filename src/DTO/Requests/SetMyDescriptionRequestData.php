<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setMyDescription`.
 */
final readonly class SetMyDescriptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setMyDescription';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?string $description = null,
        ?string $languageCode = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'description' => $description,
            'language_code' => $languageCode,
        ], $extra)));
    }
}
