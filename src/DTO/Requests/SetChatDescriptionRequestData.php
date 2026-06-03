<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setChatDescription`.
 */
final readonly class SetChatDescriptionRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setChatDescription';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?string $description = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'description' => $description,
        ], $extra)));
    }
}
