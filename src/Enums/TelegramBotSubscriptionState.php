<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramBotSubscriptionState: string
{
    case Canceled = 'canceled';
    case Active = 'active';
    case Failed = 'failed';
}
