<?php

namespace App\Listeners;

use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookDuplicateSkipped;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookFailed;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookHandled;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookQueued;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Illuminate\Support\Facades\Log;

class RecordTelegramWebhookMetric
{
    public function handle(
        TelegramWebhookReceived|TelegramWebhookHandled|TelegramWebhookFailed|TelegramWebhookQueued|TelegramWebhookDuplicateSkipped $event,
    ): void {
        Log::info('Telegram webhook event.', [
            'event' => class_basename($event),
            'bot' => $event->botName,
            'update_id' => $event->update->updateId(),
            'update_type' => $event->update->type(),
        ]);
    }
}
