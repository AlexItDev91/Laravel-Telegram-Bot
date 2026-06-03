<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiMethodSchema
{
    public const CHECKSUM = '530ff42016e193d4d7348a53709fa53cb5e131b1db545e22227c98484432f7ec';

    /**
     * @var array<string, list<array{name: string, type: string, required: bool}>>
     */
    private const PARAMETERS = [
        'addStickerToSet' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'sticker',
                'type' => 'InputSticker',
                'required' => true,
            ],
        ],
        'answerCallbackQuery' => [
            [
                'name' => 'callback_query_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'text',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'show_alert',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'url',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'cache_time',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'answerGuestQuery' => [
            [
                'name' => 'guest_query_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'result',
                'type' => 'InlineQueryResult',
                'required' => true,
            ],
        ],
        'answerInlineQuery' => [
            [
                'name' => 'inline_query_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'results',
                'type' => 'Array of InlineQueryResult',
                'required' => true,
            ],
            [
                'name' => 'cache_time',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'is_personal',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'next_offset',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'button',
                'type' => 'InlineQueryResultsButton',
                'required' => false,
            ],
        ],
        'answerPreCheckoutQuery' => [
            [
                'name' => 'pre_checkout_query_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'ok',
                'type' => 'Boolean',
                'required' => true,
            ],
            [
                'name' => 'error_message',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'answerShippingQuery' => [
            [
                'name' => 'shipping_query_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'ok',
                'type' => 'Boolean',
                'required' => true,
            ],
            [
                'name' => 'shipping_options',
                'type' => 'Array of ShippingOption',
                'required' => false,
            ],
            [
                'name' => 'error_message',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'answerWebAppQuery' => [
            [
                'name' => 'web_app_query_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'result',
                'type' => 'InlineQueryResult',
                'required' => true,
            ],
        ],
        'approveChatJoinRequest' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'approveSuggestedPost' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'send_date',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'banChatMember' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'until_date',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'revoke_messages',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'banChatSenderChat' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'sender_chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'close' => [
        ],
        'closeForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'closeGeneralForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'convertGiftToStars' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'owned_gift_id',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'copyMessage' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'from_chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'video_start_timestamp',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'copyMessages' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'from_chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_ids',
                'type' => 'Array of Integer',
                'required' => true,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'remove_caption',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'createChatInviteLink' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'expire_date',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'member_limit',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'creates_join_request',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'createChatSubscriptionInviteLink' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'subscription_period',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'subscription_price',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'createForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'icon_color',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'icon_custom_emoji_id',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'createInvoiceLink' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'description',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'payload',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'provider_token',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'currency',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'prices',
                'type' => 'Array of LabeledPrice',
                'required' => true,
            ],
            [
                'name' => 'subscription_period',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'max_tip_amount',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'suggested_tip_amounts',
                'type' => 'Array of Integer',
                'required' => false,
            ],
            [
                'name' => 'provider_data',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'photo_url',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'photo_size',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'photo_width',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'photo_height',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'need_name',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'need_phone_number',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'need_email',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'need_shipping_address',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'send_phone_number_to_provider',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'send_email_to_provider',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'is_flexible',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'createNewStickerSet' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'stickers',
                'type' => 'Array of InputSticker',
                'required' => true,
            ],
            [
                'name' => 'sticker_type',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'needs_repainting',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'declineChatJoinRequest' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'declineSuggestedPost' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'comment',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'deleteAllMessageReactions' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'actor_chat_id',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'deleteBusinessMessages' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'message_ids',
                'type' => 'Array of Integer',
                'required' => true,
            ],
        ],
        'deleteChatPhoto' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'deleteChatStickerSet' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'deleteForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'deleteMessage' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'deleteMessageReaction' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'actor_chat_id',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'deleteMessages' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_ids',
                'type' => 'Array of Integer',
                'required' => true,
            ],
        ],
        'deleteMyCommands' => [
            [
                'name' => 'scope',
                'type' => 'BotCommandScope',
                'required' => false,
            ],
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'deleteStickerFromSet' => [
            [
                'name' => 'sticker',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'deleteStickerSet' => [
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'deleteStory' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'story_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'deleteWebhook' => [
            [
                'name' => 'drop_pending_updates',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'editChatInviteLink' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'invite_link',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'expire_date',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'member_limit',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'creates_join_request',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'editChatSubscriptionInviteLink' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'invite_link',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'editForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'icon_custom_emoji_id',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'editGeneralForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'editMessageCaption' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'editMessageChecklist' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'checklist',
                'type' => 'InputChecklist',
                'required' => true,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'editMessageLiveLocation' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'latitude',
                'type' => 'Float',
                'required' => true,
            ],
            [
                'name' => 'longitude',
                'type' => 'Float',
                'required' => true,
            ],
            [
                'name' => 'live_period',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'horizontal_accuracy',
                'type' => 'Float',
                'required' => false,
            ],
            [
                'name' => 'heading',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'proximity_alert_radius',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'editMessageMedia' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'media',
                'type' => 'InputMedia',
                'required' => true,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'editMessageReplyMarkup' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'editMessageText' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'text',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'link_preview_options',
                'type' => 'LinkPreviewOptions',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'editStory' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'story_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'content',
                'type' => 'InputStoryContent',
                'required' => true,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'areas',
                'type' => 'Array of StoryArea',
                'required' => false,
            ],
        ],
        'editUserStarSubscription' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'telegram_payment_charge_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'is_canceled',
                'type' => 'Boolean',
                'required' => true,
            ],
        ],
        'exportChatInviteLink' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'forwardMessage' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'from_chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'video_start_timestamp',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'forwardMessages' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'from_chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_ids',
                'type' => 'Array of Integer',
                'required' => true,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'getAvailableGifts' => [
        ],
        'getBusinessAccountGifts' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'exclude_unsaved',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_saved',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_unlimited',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_limited_upgradable',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_limited_non_upgradable',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_unique',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_from_blockchain',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'sort_by_price',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'offset',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getBusinessAccountStarBalance' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'getBusinessConnection' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'getChat' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'getChatAdministrators' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'return_bots',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'getChatGifts' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'exclude_unsaved',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_saved',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_unlimited',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_limited_upgradable',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_limited_non_upgradable',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_from_blockchain',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_unique',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'sort_by_price',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'offset',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getChatMember' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'getChatMemberCount' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'getChatMenuButton' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getCustomEmojiStickers' => [
            [
                'name' => 'custom_emoji_ids',
                'type' => 'Array of String',
                'required' => true,
            ],
        ],
        'getFile' => [
            [
                'name' => 'file_id',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'getForumTopicIconStickers' => [
        ],
        'getGameHighScores' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'getManagedBotAccessSettings' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'getManagedBotToken' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'getMe' => [
        ],
        'getMyCommands' => [
            [
                'name' => 'scope',
                'type' => 'BotCommandScope',
                'required' => false,
            ],
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'getMyDefaultAdministratorRights' => [
            [
                'name' => 'for_channels',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'getMyDescription' => [
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'getMyName' => [
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'getMyShortDescription' => [
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'getMyStarBalance' => [
        ],
        'getStarTransactions' => [
            [
                'name' => 'offset',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getStickerSet' => [
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'getUpdates' => [
            [
                'name' => 'offset',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'timeout',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'allowed_updates',
                'type' => 'Array of String',
                'required' => false,
            ],
        ],
        'getUserChatBoosts' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'getUserGifts' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'exclude_unlimited',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_limited_upgradable',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_limited_non_upgradable',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_from_blockchain',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'exclude_unique',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'sort_by_price',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'offset',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getUserPersonalChatMessages' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'getUserProfileAudios' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'offset',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getUserProfilePhotos' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'offset',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'limit',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'getWebhookInfo' => [
        ],
        'giftPremiumSubscription' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'month_count',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'star_count',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'text',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'text_parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'text_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
        ],
        'hideGeneralForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'leaveChat' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'logOut' => [
        ],
        'pinChatMessage' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'postStory' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'content',
                'type' => 'InputStoryContent',
                'required' => true,
            ],
            [
                'name' => 'active_period',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'areas',
                'type' => 'Array of StoryArea',
                'required' => false,
            ],
            [
                'name' => 'post_to_chat_page',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'promoteChatMember' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'is_anonymous',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_manage_chat',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_delete_messages',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_manage_video_chats',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_restrict_members',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_promote_members',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_change_info',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_invite_users',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_post_stories',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_edit_stories',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_delete_stories',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_post_messages',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_edit_messages',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_pin_messages',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_manage_topics',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_manage_direct_messages',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'can_manage_tags',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'readBusinessMessage' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'refundStarPayment' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'telegram_payment_charge_id',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'removeBusinessAccountProfilePhoto' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'is_public',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'removeChatVerification' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'removeMyProfilePhoto' => [
        ],
        'removeUserVerification' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'reopenForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'reopenGeneralForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'replaceManagedBotToken' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'replaceStickerInSet' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'old_sticker',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'sticker',
                'type' => 'InputSticker',
                'required' => true,
            ],
        ],
        'repostStory' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'from_chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'from_story_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'active_period',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'post_to_chat_page',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'restrictChatMember' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'permissions',
                'type' => 'ChatPermissions',
                'required' => true,
            ],
            [
                'name' => 'use_independent_chat_permissions',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'until_date',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'revokeChatInviteLink' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'invite_link',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'savePreparedInlineMessage' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'result',
                'type' => 'InlineQueryResult',
                'required' => true,
            ],
            [
                'name' => 'allow_user_chats',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_bot_chats',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_group_chats',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_channel_chats',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'savePreparedKeyboardButton' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'button',
                'type' => 'KeyboardButton',
                'required' => true,
            ],
        ],
        'sendAnimation' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'animation',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'duration',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'width',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'height',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'thumbnail',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'has_spoiler',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendAudio' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'audio',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'duration',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'performer',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'thumbnail',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendChatAction' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'action',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'sendChecklist' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'checklist',
                'type' => 'InputChecklist',
                'required' => true,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'sendContact' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'phone_number',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'first_name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'last_name',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'vcard',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendDice' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'emoji',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendDocument' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'document',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'thumbnail',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'disable_content_type_detection',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendGame' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'game_short_name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'sendGift' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'gift_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'pay_for_upgrade',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'text',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'text_parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'text_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
        ],
        'sendInvoice' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'description',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'payload',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'provider_token',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'currency',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'prices',
                'type' => 'Array of LabeledPrice',
                'required' => true,
            ],
            [
                'name' => 'max_tip_amount',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'suggested_tip_amounts',
                'type' => 'Array of Integer',
                'required' => false,
            ],
            [
                'name' => 'start_parameter',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'provider_data',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'photo_url',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'photo_size',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'photo_width',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'photo_height',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'need_name',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'need_phone_number',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'need_email',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'need_shipping_address',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'send_phone_number_to_provider',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'send_email_to_provider',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'is_flexible',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'sendLivePhoto' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'live_photo',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'photo',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'has_spoiler',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendLocation' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'latitude',
                'type' => 'Float',
                'required' => true,
            ],
            [
                'name' => 'longitude',
                'type' => 'Float',
                'required' => true,
            ],
            [
                'name' => 'horizontal_accuracy',
                'type' => 'Float',
                'required' => false,
            ],
            [
                'name' => 'live_period',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'heading',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'proximity_alert_radius',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendMediaGroup' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'media',
                'type' => 'Array of InputMediaAudio, InputMediaDocument, InputMediaLivePhoto, InputMediaPhoto and InputMediaVideo',
                'required' => true,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
        ],
        'sendMessage' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'text',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'link_preview_options',
                'type' => 'LinkPreviewOptions',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendMessageDraft' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'draft_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'text',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
        ],
        'sendPaidMedia' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'star_count',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'media',
                'type' => 'Array of InputPaidMedia',
                'required' => true,
            ],
            [
                'name' => 'payload',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendPhoto' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'photo',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'has_spoiler',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendPoll' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'question',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'question_parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'question_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'options',
                'type' => 'Array of InputPollOption',
                'required' => true,
            ],
            [
                'name' => 'is_anonymous',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'type',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'allows_multiple_answers',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allows_revoting',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'shuffle_options',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_adding_options',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'hide_results_until_closes',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'members_only',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'country_codes',
                'type' => 'Array of String',
                'required' => false,
            ],
            [
                'name' => 'correct_option_ids',
                'type' => 'Array of Integer',
                'required' => false,
            ],
            [
                'name' => 'explanation',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'explanation_parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'explanation_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'explanation_media',
                'type' => 'InputPollMedia',
                'required' => false,
            ],
            [
                'name' => 'open_period',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'close_date',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'is_closed',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'description',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'description_parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'description_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'media',
                'type' => 'InputPollMedia',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendSticker' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'sticker',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'emoji',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendVenue' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'latitude',
                'type' => 'Float',
                'required' => true,
            ],
            [
                'name' => 'longitude',
                'type' => 'Float',
                'required' => true,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'address',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'foursquare_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'foursquare_type',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'google_place_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'google_place_type',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendVideo' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'video',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'duration',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'width',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'height',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'thumbnail',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'cover',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'start_timestamp',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'show_caption_above_media',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'has_spoiler',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'supports_streaming',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendVideoNote' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'video_note',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'duration',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'length',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'thumbnail',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'sendVoice' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'direct_messages_topic_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'voice',
                'type' => 'InputFile or String',
                'required' => true,
            ],
            [
                'name' => 'caption',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'parse_mode',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'caption_entities',
                'type' => 'Array of MessageEntity',
                'required' => false,
            ],
            [
                'name' => 'duration',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'disable_notification',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'protect_content',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'allow_paid_broadcast',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'message_effect_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'suggested_post_parameters',
                'type' => 'SuggestedPostParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_parameters',
                'type' => 'ReplyParameters',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply',
                'required' => false,
            ],
        ],
        'setBusinessAccountBio' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'bio',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setBusinessAccountGiftSettings' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'show_gift_button',
                'type' => 'Boolean',
                'required' => true,
            ],
            [
                'name' => 'accepted_gift_types',
                'type' => 'AcceptedGiftTypes',
                'required' => true,
            ],
        ],
        'setBusinessAccountName' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'first_name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'last_name',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setBusinessAccountProfilePhoto' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'photo',
                'type' => 'InputProfilePhoto',
                'required' => true,
            ],
            [
                'name' => 'is_public',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'setBusinessAccountUsername' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'username',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setChatAdministratorCustomTitle' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'custom_title',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'setChatDescription' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'description',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setChatMemberTag' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'tag',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setChatMenuButton' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'menu_button',
                'type' => 'MenuButton',
                'required' => false,
            ],
        ],
        'setChatPermissions' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'permissions',
                'type' => 'ChatPermissions',
                'required' => true,
            ],
            [
                'name' => 'use_independent_chat_permissions',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'setChatPhoto' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'photo',
                'type' => 'InputFile',
                'required' => true,
            ],
        ],
        'setChatStickerSet' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'sticker_set_name',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'setChatTitle' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'setCustomEmojiStickerSetThumbnail' => [
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'custom_emoji_id',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setGameScore' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'score',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'force',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'disable_edit_message',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setManagedBotAccessSettings' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'is_access_restricted',
                'type' => 'Boolean',
                'required' => true,
            ],
            [
                'name' => 'added_user_ids',
                'type' => 'Array of Integer',
                'required' => false,
            ],
        ],
        'setMessageReaction' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'reaction',
                'type' => 'Array of ReactionType',
                'required' => false,
            ],
            [
                'name' => 'is_big',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'setMyCommands' => [
            [
                'name' => 'commands',
                'type' => 'Array of BotCommand',
                'required' => true,
            ],
            [
                'name' => 'scope',
                'type' => 'BotCommandScope',
                'required' => false,
            ],
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setMyDefaultAdministratorRights' => [
            [
                'name' => 'rights',
                'type' => 'ChatAdministratorRights',
                'required' => false,
            ],
            [
                'name' => 'for_channels',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'setMyDescription' => [
            [
                'name' => 'description',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setMyName' => [
            [
                'name' => 'name',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setMyProfilePhoto' => [
            [
                'name' => 'photo',
                'type' => 'InputProfilePhoto',
                'required' => true,
            ],
        ],
        'setMyShortDescription' => [
            [
                'name' => 'short_description',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'language_code',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'setPassportDataErrors' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'errors',
                'type' => 'Array of PassportElementError',
                'required' => true,
            ],
        ],
        'setStickerEmojiList' => [
            [
                'name' => 'sticker',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'emoji_list',
                'type' => 'Array of String',
                'required' => true,
            ],
        ],
        'setStickerKeywords' => [
            [
                'name' => 'sticker',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'keywords',
                'type' => 'Array of String',
                'required' => false,
            ],
        ],
        'setStickerMaskPosition' => [
            [
                'name' => 'sticker',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'mask_position',
                'type' => 'MaskPosition',
                'required' => false,
            ],
        ],
        'setStickerPositionInSet' => [
            [
                'name' => 'sticker',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'position',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'setStickerSetThumbnail' => [
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'thumbnail',
                'type' => 'InputFile or String',
                'required' => false,
            ],
            [
                'name' => 'format',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'setStickerSetTitle' => [
            [
                'name' => 'name',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'title',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'setUserEmojiStatus' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'emoji_status_custom_emoji_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'emoji_status_expiration_date',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'setWebhook' => [
            [
                'name' => 'url',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'certificate',
                'type' => 'InputFile',
                'required' => false,
            ],
            [
                'name' => 'ip_address',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'max_connections',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'allowed_updates',
                'type' => 'Array of String',
                'required' => false,
            ],
            [
                'name' => 'drop_pending_updates',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'secret_token',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'stopMessageLiveLocation' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => false,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
            [
                'name' => 'inline_message_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'stopPoll' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'reply_markup',
                'type' => 'InlineKeyboardMarkup',
                'required' => false,
            ],
        ],
        'transferBusinessAccountStars' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'star_count',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'transferGift' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'owned_gift_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'new_owner_chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'star_count',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'unbanChatMember' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'only_if_banned',
                'type' => 'Boolean',
                'required' => false,
            ],
        ],
        'unbanChatSenderChat' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'sender_chat_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'unhideGeneralForumTopic' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'unpinAllChatMessages' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'unpinAllForumTopicMessages' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_thread_id',
                'type' => 'Integer',
                'required' => true,
            ],
        ],
        'unpinAllGeneralForumTopicMessages' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
        ],
        'unpinChatMessage' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => false,
            ],
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'message_id',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'upgradeGift' => [
            [
                'name' => 'business_connection_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'owned_gift_id',
                'type' => 'String',
                'required' => true,
            ],
            [
                'name' => 'keep_original_details',
                'type' => 'Boolean',
                'required' => false,
            ],
            [
                'name' => 'star_count',
                'type' => 'Integer',
                'required' => false,
            ],
        ],
        'uploadStickerFile' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'sticker',
                'type' => 'InputFile',
                'required' => true,
            ],
            [
                'name' => 'sticker_format',
                'type' => 'String',
                'required' => true,
            ],
        ],
        'verifyChat' => [
            [
                'name' => 'chat_id',
                'type' => 'Integer or String',
                'required' => true,
            ],
            [
                'name' => 'custom_description',
                'type' => 'String',
                'required' => false,
            ],
        ],
        'verifyUser' => [
            [
                'name' => 'user_id',
                'type' => 'Integer',
                'required' => true,
            ],
            [
                'name' => 'custom_description',
                'type' => 'String',
                'required' => false,
            ],
        ],
    ];

    public static function supports(string|TelegramBotApiMethod $method): bool
    {
        return array_key_exists(self::methodName($method), self::PARAMETERS);
    }

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    public static function parameters(string|TelegramBotApiMethod $method): array
    {
        return self::PARAMETERS[self::methodName($method)] ?? [];
    }

    /**
     * @return list<string>
     */
    public static function requiredParameters(string|TelegramBotApiMethod $method): array
    {
        return array_values(array_map(
            static fn (array $parameter): string => $parameter['name'],
            array_filter(self::parameters($method), static fn (array $parameter): bool => $parameter['required']),
        ));
    }

    /**
     * @return array<string, list<array{name: string, type: string, required: bool}>>
     */
    public static function all(): array
    {
        return self::PARAMETERS;
    }

    public static function checksum(): string
    {
        return self::CHECKSUM;
    }

    private static function methodName(string|TelegramBotApiMethod $method): string
    {
        return $method instanceof TelegramBotApiMethod ? $method->value : $method;
    }
}
