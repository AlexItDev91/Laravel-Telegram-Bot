<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;

final readonly class EditMessageTextData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, array<string, mixed>>|null  $entities
     * @param  TelegramBotData|array<string, mixed>|null  $linkPreviewOptions
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        string $text,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        string|TelegramParseMode|null $parseMode = null,
        ?array $entities = null,
        TelegramBotData|array|null $linkPreviewOptions = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ) {
        self::assertMessageReference($chatId, $messageId, $inlineMessageId);

        parent::__construct(self::payload([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
            'text' => $text,
            'parse_mode' => $parseMode,
            'entities' => $entities,
            'link_preview_options' => $linkPreviewOptions,
            'reply_markup' => $replyMarkup,
        ], $extra, ['text']));
    }
}
