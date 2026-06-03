<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `removeBusinessAccountProfilePhoto`.
 */
final readonly class RemoveBusinessAccountProfilePhotoRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'removeBusinessAccountProfilePhoto';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        ?bool $isPublic = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'is_public' => $isPublic,
        ], $extra)));
    }
}
