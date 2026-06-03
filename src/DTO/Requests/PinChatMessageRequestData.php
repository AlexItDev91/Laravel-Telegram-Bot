<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `pinChatMessage`.
 */
final readonly class PinChatMessageRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'pinChatMessage';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageId,
        ?string $businessConnectionId = null,
        ?bool $disableNotification = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'business_connection_id' => $businessConnectionId,
            'disable_notification' => $disableNotification,
        ], $extra)));
    }
}
