<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiResultSchema
{
    /**
     * @var array<string, array{type: string, data_class: class-string<TelegramBotData>|null, list: bool, allows_bool: bool}>
     */
    private const array RESULTS = [
        'addStickerToSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'answerCallbackQuery' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'answerGuestQuery' => [
            'type' => 'SentGuestMessage',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramSentGuestMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'answerInlineQuery' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'answerPreCheckoutQuery' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'answerShippingQuery' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'answerWebAppQuery' => [
            'type' => 'SentWebAppMessage',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramSentWebAppMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'approveChatJoinRequest' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'approveSuggestedPost' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'banChatMember' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'banChatSenderChat' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'close' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'closeForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'closeGeneralForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'convertGiftToStars' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'copyMessage' => [
            'type' => 'MessageId',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageIdData',
            'list' => false,
            'allows_bool' => false,
        ],
        'copyMessages' => [
            'type' => 'Array<MessageId>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageIdData',
            'list' => true,
            'allows_bool' => false,
        ],
        'createChatInviteLink' => [
            'type' => 'ChatInviteLink',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatInviteLinkData',
            'list' => false,
            'allows_bool' => false,
        ],
        'createChatSubscriptionInviteLink' => [
            'type' => 'ChatInviteLink',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatInviteLinkData',
            'list' => false,
            'allows_bool' => false,
        ],
        'createForumTopic' => [
            'type' => 'ForumTopic',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramForumTopicData',
            'list' => false,
            'allows_bool' => false,
        ],
        'createInvoiceLink' => [
            'type' => 'String',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'createNewStickerSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'declineChatJoinRequest' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'declineSuggestedPost' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteAllMessageReactions' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteBusinessMessages' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteChatPhoto' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteChatStickerSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteMessage' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteMessageReaction' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteMessages' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteMyCommands' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteStickerFromSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteStickerSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteStory' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'deleteWebhook' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'editChatInviteLink' => [
            'type' => 'ChatInviteLink',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatInviteLinkData',
            'list' => false,
            'allows_bool' => false,
        ],
        'editChatSubscriptionInviteLink' => [
            'type' => 'ChatInviteLink',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatInviteLinkData',
            'list' => false,
            'allows_bool' => false,
        ],
        'editForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'editGeneralForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'editMessageCaption' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'editMessageChecklist' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'editMessageLiveLocation' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'editMessageMedia' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'editMessageReplyMarkup' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'editMessageText' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'editStory' => [
            'type' => 'Story',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStoryData',
            'list' => false,
            'allows_bool' => false,
        ],
        'editUserStarSubscription' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'exportChatInviteLink' => [
            'type' => 'String',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'forwardMessage' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'forwardMessages' => [
            'type' => 'Array<MessageId>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageIdData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getAvailableGifts' => [
            'type' => 'Gifts',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramGiftsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getBusinessAccountGifts' => [
            'type' => 'OwnedGifts',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramOwnedGiftsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getBusinessAccountStarBalance' => [
            'type' => 'StarAmount',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStarAmountData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getBusinessConnection' => [
            'type' => 'BusinessConnection',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBusinessConnectionData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getChat' => [
            'type' => 'ChatFullInfo',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatFullInfoData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getChatAdministrators' => [
            'type' => 'Array<ChatMember>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatMemberData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getChatGifts' => [
            'type' => 'OwnedGifts',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramOwnedGiftsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getChatMember' => [
            'type' => 'ChatMember',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatMemberData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getChatMemberCount' => [
            'type' => 'Integer',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'getChatMenuButton' => [
            'type' => 'MenuButton',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMenuButtonData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getCustomEmojiStickers' => [
            'type' => 'Array<Sticker>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStickerData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getFile' => [
            'type' => 'File',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramFileData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getForumTopicIconStickers' => [
            'type' => 'Array<Sticker>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStickerData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getGameHighScores' => [
            'type' => 'Array<GameHighScore>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramGameHighScoreData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getManagedBotAccessSettings' => [
            'type' => 'BotAccessSettings',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBotAccessSettingsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getManagedBotToken' => [
            'type' => 'String',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'getMe' => [
            'type' => 'User',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramUserData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getMyCommands' => [
            'type' => 'Array<BotCommand>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBotCommandData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getMyDefaultAdministratorRights' => [
            'type' => 'ChatAdministratorRights',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatAdministratorRightsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getMyDescription' => [
            'type' => 'BotDescription',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBotDescriptionData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getMyName' => [
            'type' => 'BotName',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBotNameData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getMyShortDescription' => [
            'type' => 'BotShortDescription',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBotShortDescriptionData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getMyStarBalance' => [
            'type' => 'StarAmount',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStarAmountData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getStarTransactions' => [
            'type' => 'StarTransactions',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStarTransactionsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getStickerSet' => [
            'type' => 'StickerSet',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStickerSetData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getUpdates' => [
            'type' => 'Array<Update>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramWebhookUpdate',
            'list' => true,
            'allows_bool' => false,
        ],
        'getUserChatBoosts' => [
            'type' => 'UserChatBoosts',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramUserChatBoostsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getUserGifts' => [
            'type' => 'OwnedGifts',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramOwnedGiftsData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getUserPersonalChatMessages' => [
            'type' => 'Array<Message>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => true,
            'allows_bool' => false,
        ],
        'getUserProfileAudios' => [
            'type' => 'UserProfileAudios',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramUserProfileAudiosData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getUserProfilePhotos' => [
            'type' => 'UserProfilePhotos',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramUserProfilePhotosData',
            'list' => false,
            'allows_bool' => false,
        ],
        'getWebhookInfo' => [
            'type' => 'WebhookInfo',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramWebhookInfoData',
            'list' => false,
            'allows_bool' => false,
        ],
        'giftPremiumSubscription' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'hideGeneralForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'leaveChat' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'logOut' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'pinChatMessage' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'postStory' => [
            'type' => 'Story',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStoryData',
            'list' => false,
            'allows_bool' => false,
        ],
        'promoteChatMember' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'readBusinessMessage' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'refundStarPayment' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'removeBusinessAccountProfilePhoto' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'removeChatVerification' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'removeMyProfilePhoto' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'removeUserVerification' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'reopenForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'reopenGeneralForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'replaceManagedBotToken' => [
            'type' => 'String',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'replaceStickerInSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'repostStory' => [
            'type' => 'Story',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramStoryData',
            'list' => false,
            'allows_bool' => false,
        ],
        'restrictChatMember' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'revokeChatInviteLink' => [
            'type' => 'ChatInviteLink',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramChatInviteLinkData',
            'list' => false,
            'allows_bool' => false,
        ],
        'savePreparedInlineMessage' => [
            'type' => 'PreparedInlineMessage',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramPreparedInlineMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'savePreparedKeyboardButton' => [
            'type' => 'PreparedKeyboardButton',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramPreparedKeyboardButtonData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendAnimation' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendAudio' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendChatAction' => [
            'type' => 'mixed',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'sendChecklist' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendContact' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendDice' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendDocument' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendGame' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendGift' => [
            'type' => 'mixed',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'sendInvoice' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendLivePhoto' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendLocation' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendMediaGroup' => [
            'type' => 'Array<Message>',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => true,
            'allows_bool' => false,
        ],
        'sendMessage' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendMessageDraft' => [
            'type' => 'mixed',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'sendPaidMedia' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendPhoto' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendPoll' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendSticker' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendVenue' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendVideo' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendVideoNote' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'sendVoice' => [
            'type' => 'Message',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => false,
        ],
        'setBusinessAccountBio' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setBusinessAccountGiftSettings' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setBusinessAccountName' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setBusinessAccountProfilePhoto' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setBusinessAccountUsername' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatAdministratorCustomTitle' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatDescription' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatMemberTag' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatMenuButton' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatPermissions' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatPhoto' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatStickerSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setChatTitle' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setCustomEmojiStickerSetThumbnail' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setGameScore' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'setManagedBotAccessSettings' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMessageReaction' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMyCommands' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMyDefaultAdministratorRights' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMyDescription' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMyName' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMyProfilePhoto' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setMyShortDescription' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setPassportDataErrors' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setStickerEmojiList' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setStickerKeywords' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setStickerMaskPosition' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setStickerPositionInSet' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setStickerSetThumbnail' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setStickerSetTitle' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setUserEmojiStatus' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'setWebhook' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'stopMessageLiveLocation' => [
            'type' => 'Message|Boolean',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramMessageData',
            'list' => false,
            'allows_bool' => true,
        ],
        'stopPoll' => [
            'type' => 'Poll',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramPollData',
            'list' => false,
            'allows_bool' => false,
        ],
        'transferBusinessAccountStars' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'transferGift' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unbanChatMember' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unbanChatSenderChat' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unhideGeneralForumTopic' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unpinAllChatMessages' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unpinAllForumTopicMessages' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unpinAllGeneralForumTopicMessages' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'unpinChatMessage' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'upgradeGift' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'uploadStickerFile' => [
            'type' => 'File',
            'data_class' => 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramFileData',
            'list' => false,
            'allows_bool' => false,
        ],
        'verifyChat' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
        'verifyUser' => [
            'type' => 'Boolean',
            'data_class' => NULL,
            'list' => false,
            'allows_bool' => false,
        ],
    ];

    /**
     * @return array{type: string, data_class: class-string<TelegramBotData>|null, list: bool, allows_bool: bool}
     */
    public static function result(string|TelegramBotApiMethod $method): array
    {
        return self::RESULTS[self::methodName($method)] ?? [
            'type' => 'mixed',
            'data_class' => null,
            'list' => false,
            'allows_bool' => false,
        ];
    }

    public static function type(string|TelegramBotApiMethod $method): string
    {
        return self::result($method)['type'];
    }

    /**
     * @return class-string<TelegramBotData>|null
     */
    public static function dataClass(string|TelegramBotApiMethod $method): ?string
    {
        return self::result($method)['data_class'];
    }

    public static function isList(string|TelegramBotApiMethod $method): bool
    {
        return self::result($method)['list'];
    }

    public static function allowsBool(string|TelegramBotApiMethod $method): bool
    {
        return self::result($method)['allows_bool'];
    }

    /**
     * @return array<string, array{type: string, data_class: class-string<TelegramBotData>|null, list: bool, allows_bool: bool}>
     */
    public static function all(): array
    {
        return self::RESULTS;
    }

    private static function methodName(string|TelegramBotApiMethod $method): string
    {
        return $method instanceof TelegramBotApiMethod ? $method->value : $method;
    }
}
