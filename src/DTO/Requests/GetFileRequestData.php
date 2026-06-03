<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getFile`.
 */
final readonly class GetFileRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getFile';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $fileId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'file_id' => $fileId,
        ], $extra)));
    }
}
