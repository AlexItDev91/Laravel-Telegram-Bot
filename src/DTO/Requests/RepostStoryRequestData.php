<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `repostStory`.
 */
final readonly class RepostStoryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'repostStory';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int $fromChatId,
        int $fromStoryId,
        int $activePeriod,
        ?bool $postToChatPage = null,
        ?bool $protectContent = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'from_chat_id' => $fromChatId,
            'from_story_id' => $fromStoryId,
            'active_period' => $activePeriod,
            'post_to_chat_page' => $postToChatPage,
            'protect_content' => $protectContent,
        ], $extra)));
    }
}
