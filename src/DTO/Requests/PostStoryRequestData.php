<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `postStory`.
 */
final readonly class PostStoryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'postStory';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $content
     * @param  array<string|int, mixed>|null  $captionEntities
     * @param  array<string|int, mixed>|null  $areas
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        TelegramBotData|array $content,
        int $activePeriod,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null,
        ?bool $postToChatPage = null,
        ?bool $protectContent = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'content' => $content,
            'active_period' => $activePeriod,
            'caption' => $caption,
            'parse_mode' => $parseMode,
            'caption_entities' => $captionEntities,
            'areas' => $areas,
            'post_to_chat_page' => $postToChatPage,
            'protect_content' => $protectContent,
        ], $extra)));
    }
}
