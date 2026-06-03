<?php

namespace App\Telegram\Commands;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookCommand;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

readonly class StartCommand implements TelegramWebhookCommandHandler
{
    private const string WELCOME_TEXT = 'Welcome. Use the menu buttons to continue.';

    public function __construct(private TelegramBot $telegram)
    {
        //
    }

    #[Override]
    public function handle(TelegramWebhookCommand $command, TelegramWebhookUpdate $update, string $botName): mixed
    {
        $chatId = $command->message()->chat()?->id();

        if ($chatId === null) {
            return null;
        }

        return $this->telegram->bot($botName)->sendMessage(new SendMessageData(
            chatId: $chatId,
            text: self::WELCOME_TEXT,
        ));
    }
}
