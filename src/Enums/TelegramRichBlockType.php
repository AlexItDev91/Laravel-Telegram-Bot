<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramRichBlockType: string
{
    case Paragraph = 'paragraph';
    case Heading = 'heading';
    case Preformatted = 'pre';
    case Footer = 'footer';
    case Divider = 'divider';
    case MathematicalExpression = 'mathematical_expression';
    case Anchor = 'anchor';
    case List = 'list';
    case BlockQuotation = 'blockquote';
    case ExpandableBlockQuotation = 'expandable_blockquote';
    case PullQuotation = 'pullquote';
    case Collage = 'collage';
    case Slideshow = 'slideshow';
    case Table = 'table';
    case Details = 'details';
    case Map = 'map';
    case Buttons = 'buttons';
    case Animation = 'animation';
    case Audio = 'audio';
    case Document = 'document';
    case Photo = 'photo';
    case Video = 'video';
    case VoiceNote = 'voice_note';
    case Thinking = 'thinking';
}
