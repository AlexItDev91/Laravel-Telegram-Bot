<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramMenuButtonType: string
{
    case Commands = 'commands';
    case WebApp = 'web_app';
    case Default = 'default';
}
