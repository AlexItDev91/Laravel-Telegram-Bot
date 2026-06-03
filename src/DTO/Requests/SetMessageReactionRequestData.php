<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setMessageReaction`.
 */
final readonly class SetMessageReactionRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setMessageReaction';

    /**
     * @param  array<string|int, mixed>|null  $reaction
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageId,
        ?array $reaction = null,
        ?bool $isBig = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'reaction' => $reaction,
            'is_big' => $isBig,
        ], $extra)));
    }
}
