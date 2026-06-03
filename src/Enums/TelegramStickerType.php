<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramStickerType: string
{
    case Regular = 'regular';
    case Mask = 'mask';
    case CustomEmoji = 'custom_emoji';
}
