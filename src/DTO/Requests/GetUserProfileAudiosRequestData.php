<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getUserProfileAudios`.
 */
final readonly class GetUserProfileAudiosRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getUserProfileAudios';

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
