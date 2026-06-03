<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

final readonly class SendMessageData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, array<string, mixed>>|null  $entities
     * @param  TelegramBotData|array<string, mixed>|null  $linkPreviewOptions
     * @param  TelegramBotData|array<string, mixed>|null  $suggestedPostParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $chatId,
        string $text,
        ?string $businessConnectionId = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
        string|TelegramParseMode|null $parseMode = null,
        ?array $entities = null,
        TelegramBotData|array|null $linkPreviewOptions = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $suggestedPostParameters = null,
        TelegramBotData|array|null $replyParameters = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_thread_id' => $messageThreadId,
            'direct_messages_topic_id' => $directMessagesTopicId,
            'text' => $text,
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
        ], $extra, ['chat_id', 'text']));
    }
}
