<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramWidgetQueryType: string
{
    case Data = 'data';
    case SignData = 'sign_data';
    case SentTransaction = 'sent_transaction';
}
