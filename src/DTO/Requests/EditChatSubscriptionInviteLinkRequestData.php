<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editChatSubscriptionInviteLink`.
 */
final readonly class EditChatSubscriptionInviteLinkRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editChatSubscriptionInviteLink';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $inviteLink,
        ?string $name = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'invite_link' => $inviteLink,
            'name' => $name,
        ], $extra)));
    }
}
