<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramPollType: string
{
    case Regular = 'regular';
    case Quiz = 'quiz';
}
