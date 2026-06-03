<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setChatMemberTag`.
 */
final readonly class SetChatMemberTagRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setChatMemberTag';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        int $userId,
        ?string $tag = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'tag' => $tag,
        ], $extra)));
    }
}
