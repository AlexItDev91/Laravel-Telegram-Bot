<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Games;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class GetGameHighScoresData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        int|string $userId,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        array $extra = [],
    ) {
        self::assertGameMessageReference($chatId, $messageId, $inlineMessageId);

        parent::__construct(self::payload([
            'user_id' => $userId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'inline_message_id' => $inlineMessageId,
        ], $extra, ['user_id']));
    }
}
