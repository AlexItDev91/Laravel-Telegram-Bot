<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramInlineQueryResultType: string
{
    case Article = 'article';
    case Photo = 'photo';
    case Gif = 'gif';
    case Mpeg4Gif = 'mpeg4_gif';
    case Video = 'video';
    case Audio = 'audio';
    case Voice = 'voice';
    case Document = 'document';
    case Location = 'location';
    case Venue = 'venue';
    case Contact = 'contact';
    case Game = 'game';
    case Sticker = 'sticker';
}
