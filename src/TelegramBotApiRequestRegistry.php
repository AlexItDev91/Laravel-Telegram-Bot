<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\DTO\Requests\AddStickerToSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerCallbackQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerGuestQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerInlineQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerPreCheckoutQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerShippingQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerWebAppQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ApproveChatJoinRequestRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ApproveSuggestedPostRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\BanChatMemberRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\BanChatSenderChatRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CloseForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CloseGeneralForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CloseRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ConvertGiftToStarsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CopyMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CopyMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CreateChatInviteLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CreateChatSubscriptionInviteLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CreateForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CreateInvoiceLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\CreateNewStickerSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeclineChatJoinRequestRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeclineSuggestedPostRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteAllMessageReactionsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteBusinessMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteChatPhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteChatStickerSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteMessageReactionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteMyCommandsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteStickerFromSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteStickerSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteStoryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\DeleteWebhookRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditChatInviteLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditChatSubscriptionInviteLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditGeneralForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageCaptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageChecklistRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageLiveLocationRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageMediaRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageReplyMarkupRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageTextRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditStoryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditUserStarSubscriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ExportChatInviteLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ForwardMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ForwardMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetAvailableGiftsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetBusinessAccountGiftsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetBusinessAccountStarBalanceRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetBusinessConnectionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetChatAdministratorsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetChatGiftsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetChatMemberCountRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetChatMemberRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetChatMenuButtonRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetChatRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetCustomEmojiStickersRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetFileRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetForumTopicIconStickersRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetGameHighScoresRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetManagedBotAccessSettingsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetManagedBotTokenRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMeRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMyCommandsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMyDefaultAdministratorRightsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMyDescriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMyNameRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMyShortDescriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetMyStarBalanceRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetStarTransactionsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetStickerSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUpdatesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUserChatBoostsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUserGiftsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUserPersonalChatMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUserProfileAudiosRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUserProfilePhotosRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetWebhookInfoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GiftPremiumSubscriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\HideGeneralForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\LeaveChatRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\LogOutRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\PinChatMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\PostStoryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\PromoteChatMemberRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ReadBusinessMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RefundStarPaymentRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RemoveBusinessAccountProfilePhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RemoveChatVerificationRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RemoveMyProfilePhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RemoveUserVerificationRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ReopenForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ReopenGeneralForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ReplaceManagedBotTokenRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\ReplaceStickerInSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RepostStoryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RestrictChatMemberRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\RevokeChatInviteLinkRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SavePreparedInlineMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SavePreparedKeyboardButtonRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendAnimationRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendAudioRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendChatActionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendChecklistRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendContactRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendDiceRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendDocumentRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendGameRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendGiftRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendInvoiceRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendLivePhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendLocationRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMediaGroupRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageDraftRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendPaidMediaRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendPhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendPollRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendStickerRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendVenueRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendVideoNoteRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendVideoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendVoiceRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetBusinessAccountBioRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetBusinessAccountGiftSettingsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetBusinessAccountNameRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetBusinessAccountProfilePhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetBusinessAccountUsernameRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatAdministratorCustomTitleRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatDescriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatMemberTagRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatMenuButtonRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatPermissionsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatPhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatStickerSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetChatTitleRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetCustomEmojiStickerSetThumbnailRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetGameScoreRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetManagedBotAccessSettingsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMessageReactionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMyCommandsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMyDefaultAdministratorRightsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMyDescriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMyNameRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMyProfilePhotoRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetMyShortDescriptionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetPassportDataErrorsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetStickerEmojiListRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetStickerKeywordsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetStickerMaskPositionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetStickerPositionInSetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetStickerSetThumbnailRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetStickerSetTitleRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetUserEmojiStatusRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SetWebhookRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\StopMessageLiveLocationRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\StopPollRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\TelegramBotApiRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\TransferBusinessAccountStarsRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\TransferGiftRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnbanChatMemberRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnbanChatSenderChatRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnhideGeneralForumTopicRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnpinAllChatMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnpinAllForumTopicMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnpinAllGeneralForumTopicMessagesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UnpinChatMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UpgradeGiftRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\UploadStickerFileRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\VerifyChatRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\VerifyUserRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiRequestRegistry
{
    /**
     * @var array<string, class-string<TelegramBotApiRequestData>>
     */
    private const array REQUESTS = [
        'addStickerToSet' => AddStickerToSetRequestData::class,
        'answerCallbackQuery' => AnswerCallbackQueryRequestData::class,
        'answerGuestQuery' => AnswerGuestQueryRequestData::class,
        'answerInlineQuery' => AnswerInlineQueryRequestData::class,
        'answerPreCheckoutQuery' => AnswerPreCheckoutQueryRequestData::class,
        'answerShippingQuery' => AnswerShippingQueryRequestData::class,
        'answerWebAppQuery' => AnswerWebAppQueryRequestData::class,
        'approveChatJoinRequest' => ApproveChatJoinRequestRequestData::class,
        'approveSuggestedPost' => ApproveSuggestedPostRequestData::class,
        'banChatMember' => BanChatMemberRequestData::class,
        'banChatSenderChat' => BanChatSenderChatRequestData::class,
        'close' => CloseRequestData::class,
        'closeForumTopic' => CloseForumTopicRequestData::class,
        'closeGeneralForumTopic' => CloseGeneralForumTopicRequestData::class,
        'convertGiftToStars' => ConvertGiftToStarsRequestData::class,
        'copyMessage' => CopyMessageRequestData::class,
        'copyMessages' => CopyMessagesRequestData::class,
        'createChatInviteLink' => CreateChatInviteLinkRequestData::class,
        'createChatSubscriptionInviteLink' => CreateChatSubscriptionInviteLinkRequestData::class,
        'createForumTopic' => CreateForumTopicRequestData::class,
        'createInvoiceLink' => CreateInvoiceLinkRequestData::class,
        'createNewStickerSet' => CreateNewStickerSetRequestData::class,
        'declineChatJoinRequest' => DeclineChatJoinRequestRequestData::class,
        'declineSuggestedPost' => DeclineSuggestedPostRequestData::class,
        'deleteAllMessageReactions' => DeleteAllMessageReactionsRequestData::class,
        'deleteBusinessMessages' => DeleteBusinessMessagesRequestData::class,
        'deleteChatPhoto' => DeleteChatPhotoRequestData::class,
        'deleteChatStickerSet' => DeleteChatStickerSetRequestData::class,
        'deleteForumTopic' => DeleteForumTopicRequestData::class,
        'deleteMessage' => DeleteMessageRequestData::class,
        'deleteMessageReaction' => DeleteMessageReactionRequestData::class,
        'deleteMessages' => DeleteMessagesRequestData::class,
        'deleteMyCommands' => DeleteMyCommandsRequestData::class,
        'deleteStickerFromSet' => DeleteStickerFromSetRequestData::class,
        'deleteStickerSet' => DeleteStickerSetRequestData::class,
        'deleteStory' => DeleteStoryRequestData::class,
        'deleteWebhook' => DeleteWebhookRequestData::class,
        'editChatInviteLink' => EditChatInviteLinkRequestData::class,
        'editChatSubscriptionInviteLink' => EditChatSubscriptionInviteLinkRequestData::class,
        'editForumTopic' => EditForumTopicRequestData::class,
        'editGeneralForumTopic' => EditGeneralForumTopicRequestData::class,
        'editMessageCaption' => EditMessageCaptionRequestData::class,
        'editMessageChecklist' => EditMessageChecklistRequestData::class,
        'editMessageLiveLocation' => EditMessageLiveLocationRequestData::class,
        'editMessageMedia' => EditMessageMediaRequestData::class,
        'editMessageReplyMarkup' => EditMessageReplyMarkupRequestData::class,
        'editMessageText' => EditMessageTextRequestData::class,
        'editStory' => EditStoryRequestData::class,
        'editUserStarSubscription' => EditUserStarSubscriptionRequestData::class,
        'exportChatInviteLink' => ExportChatInviteLinkRequestData::class,
        'forwardMessage' => ForwardMessageRequestData::class,
        'forwardMessages' => ForwardMessagesRequestData::class,
        'getAvailableGifts' => GetAvailableGiftsRequestData::class,
        'getBusinessAccountGifts' => GetBusinessAccountGiftsRequestData::class,
        'getBusinessAccountStarBalance' => GetBusinessAccountStarBalanceRequestData::class,
        'getBusinessConnection' => GetBusinessConnectionRequestData::class,
        'getChat' => GetChatRequestData::class,
        'getChatAdministrators' => GetChatAdministratorsRequestData::class,
        'getChatGifts' => GetChatGiftsRequestData::class,
        'getChatMember' => GetChatMemberRequestData::class,
        'getChatMemberCount' => GetChatMemberCountRequestData::class,
        'getChatMenuButton' => GetChatMenuButtonRequestData::class,
        'getCustomEmojiStickers' => GetCustomEmojiStickersRequestData::class,
        'getFile' => GetFileRequestData::class,
        'getForumTopicIconStickers' => GetForumTopicIconStickersRequestData::class,
        'getGameHighScores' => GetGameHighScoresRequestData::class,
        'getManagedBotAccessSettings' => GetManagedBotAccessSettingsRequestData::class,
        'getManagedBotToken' => GetManagedBotTokenRequestData::class,
        'getMe' => GetMeRequestData::class,
        'getMyCommands' => GetMyCommandsRequestData::class,
        'getMyDefaultAdministratorRights' => GetMyDefaultAdministratorRightsRequestData::class,
        'getMyDescription' => GetMyDescriptionRequestData::class,
        'getMyName' => GetMyNameRequestData::class,
        'getMyShortDescription' => GetMyShortDescriptionRequestData::class,
        'getMyStarBalance' => GetMyStarBalanceRequestData::class,
        'getStarTransactions' => GetStarTransactionsRequestData::class,
        'getStickerSet' => GetStickerSetRequestData::class,
        'getUpdates' => GetUpdatesRequestData::class,
        'getUserChatBoosts' => GetUserChatBoostsRequestData::class,
        'getUserGifts' => GetUserGiftsRequestData::class,
        'getUserPersonalChatMessages' => GetUserPersonalChatMessagesRequestData::class,
        'getUserProfileAudios' => GetUserProfileAudiosRequestData::class,
        'getUserProfilePhotos' => GetUserProfilePhotosRequestData::class,
        'getWebhookInfo' => GetWebhookInfoRequestData::class,
        'giftPremiumSubscription' => GiftPremiumSubscriptionRequestData::class,
        'hideGeneralForumTopic' => HideGeneralForumTopicRequestData::class,
        'leaveChat' => LeaveChatRequestData::class,
        'logOut' => LogOutRequestData::class,
        'pinChatMessage' => PinChatMessageRequestData::class,
        'postStory' => PostStoryRequestData::class,
        'promoteChatMember' => PromoteChatMemberRequestData::class,
        'readBusinessMessage' => ReadBusinessMessageRequestData::class,
        'refundStarPayment' => RefundStarPaymentRequestData::class,
        'removeBusinessAccountProfilePhoto' => RemoveBusinessAccountProfilePhotoRequestData::class,
        'removeChatVerification' => RemoveChatVerificationRequestData::class,
        'removeMyProfilePhoto' => RemoveMyProfilePhotoRequestData::class,
        'removeUserVerification' => RemoveUserVerificationRequestData::class,
        'reopenForumTopic' => ReopenForumTopicRequestData::class,
        'reopenGeneralForumTopic' => ReopenGeneralForumTopicRequestData::class,
        'replaceManagedBotToken' => ReplaceManagedBotTokenRequestData::class,
        'replaceStickerInSet' => ReplaceStickerInSetRequestData::class,
        'repostStory' => RepostStoryRequestData::class,
        'restrictChatMember' => RestrictChatMemberRequestData::class,
        'revokeChatInviteLink' => RevokeChatInviteLinkRequestData::class,
        'savePreparedInlineMessage' => SavePreparedInlineMessageRequestData::class,
        'savePreparedKeyboardButton' => SavePreparedKeyboardButtonRequestData::class,
        'sendAnimation' => SendAnimationRequestData::class,
        'sendAudio' => SendAudioRequestData::class,
        'sendChatAction' => SendChatActionRequestData::class,
        'sendChecklist' => SendChecklistRequestData::class,
        'sendContact' => SendContactRequestData::class,
        'sendDice' => SendDiceRequestData::class,
        'sendDocument' => SendDocumentRequestData::class,
        'sendGame' => SendGameRequestData::class,
        'sendGift' => SendGiftRequestData::class,
        'sendInvoice' => SendInvoiceRequestData::class,
        'sendLivePhoto' => SendLivePhotoRequestData::class,
        'sendLocation' => SendLocationRequestData::class,
        'sendMediaGroup' => SendMediaGroupRequestData::class,
        'sendMessage' => SendMessageRequestData::class,
        'sendMessageDraft' => SendMessageDraftRequestData::class,
        'sendPaidMedia' => SendPaidMediaRequestData::class,
        'sendPhoto' => SendPhotoRequestData::class,
        'sendPoll' => SendPollRequestData::class,
        'sendSticker' => SendStickerRequestData::class,
        'sendVenue' => SendVenueRequestData::class,
        'sendVideo' => SendVideoRequestData::class,
        'sendVideoNote' => SendVideoNoteRequestData::class,
        'sendVoice' => SendVoiceRequestData::class,
        'setBusinessAccountBio' => SetBusinessAccountBioRequestData::class,
        'setBusinessAccountGiftSettings' => SetBusinessAccountGiftSettingsRequestData::class,
        'setBusinessAccountName' => SetBusinessAccountNameRequestData::class,
        'setBusinessAccountProfilePhoto' => SetBusinessAccountProfilePhotoRequestData::class,
        'setBusinessAccountUsername' => SetBusinessAccountUsernameRequestData::class,
        'setChatAdministratorCustomTitle' => SetChatAdministratorCustomTitleRequestData::class,
        'setChatDescription' => SetChatDescriptionRequestData::class,
        'setChatMemberTag' => SetChatMemberTagRequestData::class,
        'setChatMenuButton' => SetChatMenuButtonRequestData::class,
        'setChatPermissions' => SetChatPermissionsRequestData::class,
        'setChatPhoto' => SetChatPhotoRequestData::class,
        'setChatStickerSet' => SetChatStickerSetRequestData::class,
        'setChatTitle' => SetChatTitleRequestData::class,
        'setCustomEmojiStickerSetThumbnail' => SetCustomEmojiStickerSetThumbnailRequestData::class,
        'setGameScore' => SetGameScoreRequestData::class,
        'setManagedBotAccessSettings' => SetManagedBotAccessSettingsRequestData::class,
        'setMessageReaction' => SetMessageReactionRequestData::class,
        'setMyCommands' => SetMyCommandsRequestData::class,
        'setMyDefaultAdministratorRights' => SetMyDefaultAdministratorRightsRequestData::class,
        'setMyDescription' => SetMyDescriptionRequestData::class,
        'setMyName' => SetMyNameRequestData::class,
        'setMyProfilePhoto' => SetMyProfilePhotoRequestData::class,
        'setMyShortDescription' => SetMyShortDescriptionRequestData::class,
        'setPassportDataErrors' => SetPassportDataErrorsRequestData::class,
        'setStickerEmojiList' => SetStickerEmojiListRequestData::class,
        'setStickerKeywords' => SetStickerKeywordsRequestData::class,
        'setStickerMaskPosition' => SetStickerMaskPositionRequestData::class,
        'setStickerPositionInSet' => SetStickerPositionInSetRequestData::class,
        'setStickerSetThumbnail' => SetStickerSetThumbnailRequestData::class,
        'setStickerSetTitle' => SetStickerSetTitleRequestData::class,
        'setUserEmojiStatus' => SetUserEmojiStatusRequestData::class,
        'setWebhook' => SetWebhookRequestData::class,
        'stopMessageLiveLocation' => StopMessageLiveLocationRequestData::class,
        'stopPoll' => StopPollRequestData::class,
        'transferBusinessAccountStars' => TransferBusinessAccountStarsRequestData::class,
        'transferGift' => TransferGiftRequestData::class,
        'unbanChatMember' => UnbanChatMemberRequestData::class,
        'unbanChatSenderChat' => UnbanChatSenderChatRequestData::class,
        'unhideGeneralForumTopic' => UnhideGeneralForumTopicRequestData::class,
        'unpinAllChatMessages' => UnpinAllChatMessagesRequestData::class,
        'unpinAllForumTopicMessages' => UnpinAllForumTopicMessagesRequestData::class,
        'unpinAllGeneralForumTopicMessages' => UnpinAllGeneralForumTopicMessagesRequestData::class,
        'unpinChatMessage' => UnpinChatMessageRequestData::class,
        'upgradeGift' => UpgradeGiftRequestData::class,
        'uploadStickerFile' => UploadStickerFileRequestData::class,
        'verifyChat' => VerifyChatRequestData::class,
        'verifyUser' => VerifyUserRequestData::class,
    ];

    /**
     * @return class-string<TelegramBotApiRequestData>|null
     */
    public static function requestClass(string|TelegramBotApiMethod $method): ?string
    {
        return self::REQUESTS[self::methodName($method)] ?? null;
    }

    /**
     * @return array<string, class-string<TelegramBotApiRequestData>>
     */
    public static function all(): array
    {
        return self::REQUESTS;
    }

    private static function methodName(string|TelegramBotApiMethod $method): string
    {
        return $method instanceof TelegramBotApiMethod ? $method->value : $method;
    }
}
