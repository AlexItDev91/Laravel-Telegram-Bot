<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteStory`.
 */
final readonly class DeleteStoryRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'deleteStory';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int $storyId,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'story_id' => $storyId,
        ], $extra)));
    }
}
