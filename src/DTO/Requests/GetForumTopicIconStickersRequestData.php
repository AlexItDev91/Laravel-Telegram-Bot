<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getForumTopicIconStickers`.
 */
final readonly class GetForumTopicIconStickersRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getForumTopicIconStickers';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
