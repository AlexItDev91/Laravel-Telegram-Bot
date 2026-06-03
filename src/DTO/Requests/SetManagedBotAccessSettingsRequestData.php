<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setManagedBotAccessSettings`.
 */
final readonly class SetManagedBotAccessSettingsRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'setManagedBotAccessSettings';

    /**
     * @param  array<string|int, mixed>|null  $addedUserIds
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        bool $isAccessRestricted,
        ?array $addedUserIds = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'is_access_restricted' => $isAccessRestricted,
            'added_user_ids' => $addedUserIds,
        ], $extra)));
    }
}
