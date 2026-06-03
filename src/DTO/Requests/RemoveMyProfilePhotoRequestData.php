<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `removeMyProfilePhoto`.
 */
final readonly class RemoveMyProfilePhotoRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'removeMyProfilePhoto';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
