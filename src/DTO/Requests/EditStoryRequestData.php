<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `editStory`.
 */
final readonly class EditStoryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editStory';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $content
     * @param  array<string|int, mixed>|null  $captionEntities
     * @param  array<string|int, mixed>|null  $areas
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int $storyId,
        TelegramBotData|array $content,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'story_id' => $storyId,
            'content' => $content,
            'caption' => $caption,
            'parse_mode' => $parseMode,
            'caption_entities' => $captionEntities,
            'areas' => $areas,
        ], $extra)));
    }
}
