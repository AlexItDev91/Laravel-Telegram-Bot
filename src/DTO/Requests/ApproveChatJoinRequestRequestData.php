<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `approveChatJoinRequest`.
 */
final readonly class ApproveChatJoinRequestRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'approveChatJoinRequest';

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
