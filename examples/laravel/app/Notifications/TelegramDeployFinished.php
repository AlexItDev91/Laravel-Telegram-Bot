<?php

namespace App\Notifications;

use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class TelegramDeployFinished extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $_notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $_notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text('Deploy finished')
            ->channel('alerts')
            ->parseMode('HTML')
            ->disableNotification();
    }
}
