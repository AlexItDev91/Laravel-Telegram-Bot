<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramChatType: string
{
    case Sender = 'sender';
    case Private = 'private';
    case Group = 'group';
    case Supergroup = 'supergroup';
    case Channel = 'channel';
}
