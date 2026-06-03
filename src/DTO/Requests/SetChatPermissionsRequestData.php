<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setChatPermissions`.
 */
final readonly class SetChatPermissionsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setChatPermissions';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $permissions
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        TelegramBotData|array $permissions,
        ?bool $useIndependentChatPermissions = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'permissions' => $permissions,
            'use_independent_chat_permissions' => $useIndependentChatPermissions,
        ], $extra)));
    }
}
