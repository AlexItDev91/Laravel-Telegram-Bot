<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendMessage`.
 */
final readonly class SendMessageRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendMessage';

    /**
     * @param  array<string|int, mixed>|null  $entities
     * @param  TelegramBotData|array<string|int, mixed>|null  $linkPreviewOptions
     * @param  TelegramBotData|array<string|int, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $text,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?string $parseMode = null,
        ?array $entities = null,
        TelegramBotData|array|null $linkPreviewOptions = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $suggestedPostParameters = null,
        TelegramBotData|array|null $replyParameters = null,
        mixed $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'text' => $text,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'parse_mode' => $parseMode,
            'entities' => $entities,
            'link_preview_options' => $linkPreviewOptions,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'message_effect_id' => $messageEffectId,
            'suggested_post_parameters' => $suggestedPostParameters,
            'reply_parameters' => $replyParameters,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
