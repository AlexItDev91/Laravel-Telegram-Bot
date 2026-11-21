<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

/**
 * Generated typed request builder for Telegram Bot API method `verifyChat`.
 */
final readonly class VerifyChatRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'verifyChat';

    /**
     * @param  array<string|int, mixed>|null  $customDescriptionEntities
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        ?string $customDescription = null,
        array $extra = [],
        string|TelegramParseMode|null $customDescriptionParseMode = null,
        ?array $customDescriptionEntities = null,
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'custom_description' => $customDescription,
            'custom_description_parse_mode' => $customDescriptionParseMode,
            'custom_description_entities' => $customDescriptionEntities,
        ], $extra)));
    }
}
