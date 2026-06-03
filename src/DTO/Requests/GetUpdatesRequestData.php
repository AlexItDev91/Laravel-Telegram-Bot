<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramUpdateType;

/**
 * Generated typed request builder for Telegram Bot API method `getUpdates`.
 */
final readonly class GetUpdatesRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'getUpdates';

    /**
     * @param  array<int, string|TelegramUpdateType>|null  $allowedUpdates
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?int $offset = null,
        ?int $limit = null,
        ?int $timeout = null,
        ?array $allowedUpdates = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'offset' => $offset,
            'limit' => $limit,
            'timeout' => $timeout,
            'allowed_updates' => $allowedUpdates,
        ], $extra)));
    }
}
