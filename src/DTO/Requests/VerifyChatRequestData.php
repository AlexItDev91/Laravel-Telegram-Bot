<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `verifyChat`.
 */
final readonly class VerifyChatRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'verifyChat';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?string $customDescription = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'custom_description' => $customDescription,
        ], $extra)));
    }
}
