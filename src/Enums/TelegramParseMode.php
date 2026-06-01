<?php

namespace Aptenova\TelegramBot\Enums;

enum TelegramParseMode: string
{
    case MarkdownV2 = 'MarkdownV2';
    case HTML = 'HTML';
    case Markdown = 'Markdown';
}
