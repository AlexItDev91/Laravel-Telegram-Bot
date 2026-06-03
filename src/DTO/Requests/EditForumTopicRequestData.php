<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editForumTopic`.
 */
final readonly class EditForumTopicRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editForumTopic';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $messageThreadId,
        ?string $name = null,
        ?string $iconCustomEmojiId = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'message_thread_id' => $messageThreadId,
            'name' => $name,
            'icon_custom_emoji_id' => $iconCustomEmojiId,
        ], $extra)));
    }
}
