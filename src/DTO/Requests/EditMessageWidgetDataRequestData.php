<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editMessageWidgetData`.
 */
final readonly class EditMessageWidgetDataRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editMessageWidgetData';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $publicData = null,
        ?string $chatData = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
            'public_data' => $publicData,
            'chat_data' => $chatData,
        ], $extra)));
    }
}
