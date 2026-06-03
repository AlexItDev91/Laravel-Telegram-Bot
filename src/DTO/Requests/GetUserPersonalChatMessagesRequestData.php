<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getUserPersonalChatMessages`.
 */
final readonly class GetUserPersonalChatMessagesRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getUserPersonalChatMessages';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        int $limit,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'limit' => $limit,
        ], $extra)));
    }
}
