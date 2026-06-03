<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getUserProfilePhotos`.
 */
final readonly class GetUserProfilePhotosRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getUserProfilePhotos';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        ?int $offset = null,
        ?int $limit = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'offset' => $offset,
            'limit' => $limit,
        ], $extra)));
    }
}
