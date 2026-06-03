<?php

namespace App\Telegram\Handlers;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\AnswerCallbackQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

readonly class CallbackQueryHandler implements TelegramWebhookHandler
{
    public function __construct(private readonly TelegramBot $telegram)
    {
        //
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $callbackQuery = $update->callbackQuery();

        if ($callbackQuery === null) {
            return null;
        }

        return $this->telegram->bot($botName)->answerCallbackQuery(new AnswerCallbackQueryData(
            callbackQueryId: $callbackQuery->id(),
            text: 'Saved',
            cacheTime: 30,
        ));
    }
}
