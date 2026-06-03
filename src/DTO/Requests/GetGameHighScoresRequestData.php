<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getGameHighScores`.
 */
final readonly class GetGameHighScoresRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getGameHighScores';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        ?int $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
        ], $extra)));
    }
}
