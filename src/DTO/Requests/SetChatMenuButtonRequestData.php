<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `setChatMenuButton`.
 */
final readonly class SetChatMenuButtonRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setChatMenuButton';

    /**
     * @param  TelegramBotData|array<string|int, mixed>|null  $menuButton
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?int $chatId = null,
        TelegramBotData|array|null $menuButton = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'menu_button' => $menuButton,
        ], $extra)));
    }
}
