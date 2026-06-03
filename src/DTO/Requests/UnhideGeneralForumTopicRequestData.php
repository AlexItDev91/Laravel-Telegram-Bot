<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `unhideGeneralForumTopic`.
 */
final readonly class UnhideGeneralForumTopicRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'unhideGeneralForumTopic';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
        ], $extra)));
    }
}
