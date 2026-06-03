<?php

namespace App\Telegram\Commands;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\LabeledPrice;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendInvoiceData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookCommand;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

readonly class BuyCommand implements TelegramWebhookCommandHandler
{
    public function __construct(private TelegramBot $telegram)
    {
        //
    }

    public function handle(TelegramWebhookCommand $command, TelegramWebhookUpdate $update, string $botName): mixed
    {
        $chatId = $command->message()->chat()?->id();

        if ($chatId === null) {
            return null;
        }

        return $this->telegram->bot($botName)->sendInvoice(new SendInvoiceData(
            chatId: $chatId,
            title: 'Demo purchase',
            description: 'Replace this stub with a real catalog item.',
            payload: 'demo-purchase',
            currency: 'XTR',
            prices: [
                new LabeledPrice(label: 'Demo item', amount: 1),
            ],
        ));
    }
}
