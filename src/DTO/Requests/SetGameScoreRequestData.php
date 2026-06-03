<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setGameScore`.
 */
final readonly class SetGameScoreRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setGameScore';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        int $score,
        ?bool $force = null,
        ?bool $disableEditMessage = null,
        ?int $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'score' => $score,
            'force' => $force,
            'disable_edit_message' => $disableEditMessage,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
        ], $extra)));
    }
}
