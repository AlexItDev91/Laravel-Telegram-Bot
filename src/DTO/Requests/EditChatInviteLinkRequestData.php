<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editChatInviteLink`.
 */
final readonly class EditChatInviteLinkRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editChatInviteLink';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $inviteLink,
        ?string $name = null,
        ?int $expireDate = null,
        ?int $memberLimit = null,
        ?bool $createsJoinRequest = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'invite_link' => $inviteLink,
            'name' => $name,
            'expire_date' => $expireDate,
            'member_limit' => $memberLimit,
            'creates_join_request' => $createsJoinRequest,
        ], $extra)));
    }
}
