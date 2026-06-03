<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setBusinessAccountGiftSettings`.
 */
final readonly class SetBusinessAccountGiftSettingsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setBusinessAccountGiftSettings';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $acceptedGiftTypes
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        bool $showGiftButton,
        TelegramBotData|array $acceptedGiftTypes,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'show_gift_button' => $showGiftButton,
            'accepted_gift_types' => $acceptedGiftTypes,
        ], $extra)));
    }
}
