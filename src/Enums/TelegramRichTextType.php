<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramRichTextType: string
{
    case Bold = 'bold';
    case Italic = 'italic';
    case Underline = 'underline';
    case Strikethrough = 'strikethrough';
    case Spoiler = 'spoiler';
    case Code = 'code';
    case Marked = 'marked';
    case Subscript = 'subscript';
    case Superscript = 'superscript';
    case Url = 'url';
    case EmailAddress = 'email_address';
    case PhoneNumber = 'phone_number';
    case BankCardNumber = 'bank_card_number';
    case Mention = 'mention';
    case Hashtag = 'hashtag';
    case Cashtag = 'cashtag';
    case BotCommand = 'bot_command';
    case CustomEmoji = 'custom_emoji';
    case Button = 'button';
    case MathematicalExpression = 'mathematical_expression';
    case DateTime = 'date_time';
    case TextMention = 'text_mention';
    case Anchor = 'anchor';
    case AnchorLink = 'anchor_link';
    case Reference = 'reference';
    case ReferenceLink = 'reference_link';
}
