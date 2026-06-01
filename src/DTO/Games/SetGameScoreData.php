<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Games;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class SetGameScoreData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $userId,
        int $score,
        ?bool $force = null,
        ?bool $disableEditMessage = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'user_id' => $userId,
            'score' => $score,
            'force' => $force,
            'disable_edit_message' => $disableEditMessage,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
        ], $extra));
    }
}
