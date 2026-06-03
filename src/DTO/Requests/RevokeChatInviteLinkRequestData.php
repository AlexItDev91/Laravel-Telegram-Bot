<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `revokeChatInviteLink`.
 */
final readonly class RevokeChatInviteLinkRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'revokeChatInviteLink';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $inviteLink,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'invite_link' => $inviteLink,
        ], $extra)));
    }
}
