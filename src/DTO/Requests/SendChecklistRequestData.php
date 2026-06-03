<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendChecklist`.
 */
final readonly class SendChecklistRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendChecklist';

    /**
     * @param  TelegramBotData|array<string|int, mixed>  $checklist
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $businessConnectionId,
        int|string $chatId,
        TelegramBotData|array $checklist,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $replyParameters = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'checklist' => $checklist,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'message_effect_id' => $messageEffectId,
            'reply_parameters' => $replyParameters,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
