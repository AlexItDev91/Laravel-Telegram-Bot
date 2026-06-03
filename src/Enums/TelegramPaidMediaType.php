<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramPaidMediaType: string
{
    case Preview = 'preview';
    case Photo = 'photo';
    case Video = 'video';
    case LivePhoto = 'live_photo';
}
