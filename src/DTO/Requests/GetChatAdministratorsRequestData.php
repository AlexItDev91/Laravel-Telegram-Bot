<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getChatAdministrators`.
 */
final readonly class GetChatAdministratorsRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getChatAdministrators';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?bool $returnBots = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'return_bots' => $returnBots,
        ], $extra)));
    }
}
