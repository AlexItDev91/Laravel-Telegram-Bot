<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `createChatInviteLink`.
 */
final readonly class CreateChatInviteLinkRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'createChatInviteLink';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?string $name = null,
        ?int $expireDate = null,
        ?int $memberLimit = null,
        ?bool $createsJoinRequest = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'name' => $name,
            'expire_date' => $expireDate,
            'member_limit' => $memberLimit,
            'creates_join_request' => $createsJoinRequest,
        ], $extra)));
    }
}
