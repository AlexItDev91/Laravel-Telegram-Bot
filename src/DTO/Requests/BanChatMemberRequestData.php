<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `banChatMember`.
 */
final readonly class BanChatMemberRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'banChatMember';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $userId,
        ?int $untilDate = null,
        ?bool $revokeMessages = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'until_date' => $untilDate,
            'revoke_messages' => $revokeMessages,
        ], $extra)));
    }
}
