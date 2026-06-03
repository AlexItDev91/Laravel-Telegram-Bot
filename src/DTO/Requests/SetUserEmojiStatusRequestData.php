<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `setUserEmojiStatus`.
 */
final readonly class SetUserEmojiStatusRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setUserEmojiStatus';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int $userId,
        ?string $emojiStatusCustomEmojiId = null,
        ?int $emojiStatusExpirationDate = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'user_id' => $userId,
            'emoji_status_custom_emoji_id' => $emojiStatusCustomEmojiId,
            'emoji_status_expiration_date' => $emojiStatusExpirationDate,
        ], $extra)));
    }
}
