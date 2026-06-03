<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramStickerFormat: string
{
    case Static = 'static';
    case Animated = 'animated';
    case Video = 'video';
}
