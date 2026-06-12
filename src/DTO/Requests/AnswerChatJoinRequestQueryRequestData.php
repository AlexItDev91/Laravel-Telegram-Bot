<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `answerChatJoinRequestQuery`.
 */
final readonly class AnswerChatJoinRequestQueryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'answerChatJoinRequestQuery';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $chatJoinRequestQueryId,
        string $result,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_join_request_query_id' => $chatJoinRequestQueryId,
            'result' => $result,
        ], $extra)));
    }
}
