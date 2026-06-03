<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `savePreparedInlineMessage`.
 */
final readonly class SavePreparedInlineMessageRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'savePreparedInlineMessage';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $result
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        TelegramBotData|array $result,
        ?bool $allowUserChats = null,
        ?bool $allowBotChats = null,
        ?bool $allowGroupChats = null,
        ?bool $allowChannelChats = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'result' => $result,
            'allow_user_chats' => $allowUserChats,
            'allow_bot_chats' => $allowBotChats,
            'allow_group_chats' => $allowGroupChats,
            'allow_channel_chats' => $allowChannelChats,
        ], $extra)));
    }
}
