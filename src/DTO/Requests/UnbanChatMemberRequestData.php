<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `unbanChatMember`.
 */
final readonly class UnbanChatMemberRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'unbanChatMember';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $userId,
        ?bool $onlyIfBanned = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'only_if_banned' => $onlyIfBanned,
        ], $extra)));
    }
}
