<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editGeneralForumTopic`.
 */
final readonly class EditGeneralForumTopicRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'editGeneralForumTopic';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $name,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'name' => $name,
        ], $extra)));
    }
}
