<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `declineChatJoinRequest`.
 */
final readonly class DeclineChatJoinRequestRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'declineChatJoinRequest';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $userId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
        ], $extra)));
    }
}
