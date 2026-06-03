<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setBusinessAccountProfilePhoto`.
 */
final readonly class SetBusinessAccountProfilePhotoRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setBusinessAccountProfilePhoto';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $photo
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        TelegramBotData|array $photo,
        ?bool $isPublic = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'photo' => $photo,
            'is_public' => $isPublic,
        ], $extra)));
    }
}
