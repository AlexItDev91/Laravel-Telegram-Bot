<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setChatAdministratorCustomTitle`.
 */
final readonly class SetChatAdministratorCustomTitleRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setChatAdministratorCustomTitle';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $userId,
        string $customTitle,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'custom_title' => $customTitle,
        ], $extra)));
    }
}
