<?php

namespace AlexItDev91\LaravelTelegramBot\Enums;

enum TelegramBotCommandScopeType: string
{
    case Default = 'default';
    case AllPrivateChats = 'all_private_chats';
    case AllGroupChats = 'all_group_chats';
    case AllChatAdministrators = 'all_chat_administrators';
    case Chat = 'chat';
    case ChatAdministrators = 'chat_administrators';
    case ChatMember = 'chat_member';
}
