<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `restrictChatMember`.
 */
final readonly class RestrictChatMemberRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'restrictChatMember';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $permissions
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $userId,
        TelegramBotData|array $permissions,
        ?bool $useIndependentChatPermissions = null,
        ?int $untilDate = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'permissions' => $permissions,
            'use_independent_chat_permissions' => $useIndependentChatPermissions,
            'until_date' => $untilDate,
        ], $extra)));
    }
}
