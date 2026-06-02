<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Games;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class SendGameData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $replyParameters
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $chatId,
        string $gameShortName,
        int|string|null $businessConnectionId = null,
        int|string|null $messageThreadId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $replyParameters = null,
        TelegramBotData|array|null $replyMarkup = null,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'business_connection_id' => $businessConnectionId,
            'chat_id' => $chatId,
            'message_thread_id' => $messageThreadId,
            'game_short_name' => $gameShortName,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'message_effect_id' => $messageEffectId,
            'reply_parameters' => $replyParameters,
            'reply_markup' => $replyMarkup,
        ], $extra, ['chat_id', 'game_short_name']));
    }
}
