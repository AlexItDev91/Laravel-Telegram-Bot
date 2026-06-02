# Telegram Bot API Method Support

This package targets Telegram Bot API 10.0, released on 2026-05-08.

Primary sources:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Telegram Bot API changelog](https://core.telegram.org/bots/api-changelog)

Every method below is exposed as:

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot as TelegramBotService;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

public function __construct(
    private TelegramBotService $telegram,
) {
}

$result = $this->telegram->channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);

$result = TelegramBot::bot('default')->sendMessage([
    'chat_id' => '-1001234567890',
    'text' => 'Hello',
]);

$result = TelegramBot::channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);

$result = TelegramBot::call('newTelegramMethod', [
    'parameter' => 'value',
]);
```

The raw `call(method, parameters)` API is intentionally retained so newly released Telegram methods can be used before the typed SDK surface is updated.

## Supported Methods

| Method | SDK call | Official source |
| --- | --- | --- |
| `addStickerToSet` | `addStickerToSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#addstickertoset) |
| `answerCallbackQuery` | `answerCallbackQuery(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#answercallbackquery) |
| `answerGuestQuery` | `answerGuestQuery(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#answerguestquery) |
| `answerInlineQuery` | `answerInlineQuery(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#answerinlinequery) |
| `answerPreCheckoutQuery` | `answerPreCheckoutQuery(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#answerprecheckoutquery) |
| `answerShippingQuery` | `answerShippingQuery(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#answershippingquery) |
| `answerWebAppQuery` | `answerWebAppQuery(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#answerwebappquery) |
| `approveChatJoinRequest` | `approveChatJoinRequest(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#approvechatjoinrequest) |
| `approveSuggestedPost` | `approveSuggestedPost(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#approvesuggestedpost) |
| `banChatMember` | `banChatMember(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#banchatmember) |
| `banChatSenderChat` | `banChatSenderChat(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#banchatsenderchat) |
| `close` | `close(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#close) |
| `closeForumTopic` | `closeForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#closeforumtopic) |
| `closeGeneralForumTopic` | `closeGeneralForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#closegeneralforumtopic) |
| `convertGiftToStars` | `convertGiftToStars(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#convertgifttostars) |
| `copyMessage` | `copyMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#copymessage) |
| `copyMessages` | `copyMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#copymessages) |
| `createChatInviteLink` | `createChatInviteLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#createchatinvitelink) |
| `createChatSubscriptionInviteLink` | `createChatSubscriptionInviteLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#createchatsubscriptioninvitelink) |
| `createForumTopic` | `createForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#createforumtopic) |
| `createInvoiceLink` | `createInvoiceLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#createinvoicelink) |
| `createNewStickerSet` | `createNewStickerSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#createnewstickerset) |
| `declineChatJoinRequest` | `declineChatJoinRequest(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#declinechatjoinrequest) |
| `declineSuggestedPost` | `declineSuggestedPost(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#declinesuggestedpost) |
| `deleteAllMessageReactions` | `deleteAllMessageReactions(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deleteallmessagereactions) |
| `deleteBusinessMessages` | `deleteBusinessMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletebusinessmessages) |
| `deleteChatPhoto` | `deleteChatPhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletechatphoto) |
| `deleteChatStickerSet` | `deleteChatStickerSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletechatstickerset) |
| `deleteForumTopic` | `deleteForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deleteforumtopic) |
| `deleteMessage` | `deleteMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletemessage) |
| `deleteMessageReaction` | `deleteMessageReaction(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletemessagereaction) |
| `deleteMessages` | `deleteMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletemessages) |
| `deleteMyCommands` | `deleteMyCommands(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletemycommands) |
| `deleteStickerFromSet` | `deleteStickerFromSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletestickerfromset) |
| `deleteStickerSet` | `deleteStickerSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletestickerset) |
| `deleteStory` | `deleteStory(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletestory) |
| `deleteWebhook` | `deleteWebhook(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#deletewebhook) |
| `editChatInviteLink` | `editChatInviteLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editchatinvitelink) |
| `editChatSubscriptionInviteLink` | `editChatSubscriptionInviteLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editchatsubscriptioninvitelink) |
| `editForumTopic` | `editForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editforumtopic) |
| `editGeneralForumTopic` | `editGeneralForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editgeneralforumtopic) |
| `editMessageCaption` | `editMessageCaption(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editmessagecaption) |
| `editMessageChecklist` | `editMessageChecklist(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editmessagechecklist) |
| `editMessageLiveLocation` | `editMessageLiveLocation(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editmessagelivelocation) |
| `editMessageMedia` | `editMessageMedia(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editmessagemedia) |
| `editMessageReplyMarkup` | `editMessageReplyMarkup(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editmessagereplymarkup) |
| `editMessageText` | `editMessageText(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editmessagetext) |
| `editStory` | `editStory(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#editstory) |
| `editUserStarSubscription` | `editUserStarSubscription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#edituserstarsubscription) |
| `exportChatInviteLink` | `exportChatInviteLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#exportchatinvitelink) |
| `forwardMessage` | `forwardMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#forwardmessage) |
| `forwardMessages` | `forwardMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#forwardmessages) |
| `getAvailableGifts` | `getAvailableGifts(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getavailablegifts) |
| `getBusinessAccountGifts` | `getBusinessAccountGifts(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getbusinessaccountgifts) |
| `getBusinessAccountStarBalance` | `getBusinessAccountStarBalance(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getbusinessaccountstarbalance) |
| `getBusinessConnection` | `getBusinessConnection(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getbusinessconnection) |
| `getChat` | `getChat(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getchat) |
| `getChatAdministrators` | `getChatAdministrators(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getchatadministrators) |
| `getChatGifts` | `getChatGifts(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getchatgifts) |
| `getChatMember` | `getChatMember(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getchatmember) |
| `getChatMemberCount` | `getChatMemberCount(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getchatmembercount) |
| `getChatMenuButton` | `getChatMenuButton(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getchatmenubutton) |
| `getCustomEmojiStickers` | `getCustomEmojiStickers(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getcustomemojistickers) |
| `getFile` | `getFile(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getfile) |
| `getForumTopicIconStickers` | `getForumTopicIconStickers(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getforumtopiciconstickers) |
| `getGameHighScores` | `getGameHighScores(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getgamehighscores) |
| `getManagedBotAccessSettings` | `getManagedBotAccessSettings(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmanagedbotaccesssettings) |
| `getManagedBotToken` | `getManagedBotToken(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmanagedbottoken) |
| `getMe` | `getMe(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getme) |
| `getMyCommands` | `getMyCommands(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmycommands) |
| `getMyDefaultAdministratorRights` | `getMyDefaultAdministratorRights(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmydefaultadministratorrights) |
| `getMyDescription` | `getMyDescription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmydescription) |
| `getMyName` | `getMyName(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmyname) |
| `getMyShortDescription` | `getMyShortDescription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmyshortdescription) |
| `getMyStarBalance` | `getMyStarBalance(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getmystarbalance) |
| `getStarTransactions` | `getStarTransactions(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getstartransactions) |
| `getStickerSet` | `getStickerSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getstickerset) |
| `getUpdates` | `getUpdates(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getupdates) |
| `getUserChatBoosts` | `getUserChatBoosts(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getuserchatboosts) |
| `getUserGifts` | `getUserGifts(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getusergifts) |
| `getUserPersonalChatMessages` | `getUserPersonalChatMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getuserpersonalchatmessages) |
| `getUserProfileAudios` | `getUserProfileAudios(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getuserprofileaudios) |
| `getUserProfilePhotos` | `getUserProfilePhotos(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getuserprofilephotos) |
| `getWebhookInfo` | `getWebhookInfo(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#getwebhookinfo) |
| `giftPremiumSubscription` | `giftPremiumSubscription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#giftpremiumsubscription) |
| `hideGeneralForumTopic` | `hideGeneralForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#hidegeneralforumtopic) |
| `leaveChat` | `leaveChat(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#leavechat) |
| `logOut` | `logOut(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#logout) |
| `pinChatMessage` | `pinChatMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#pinchatmessage) |
| `postStory` | `postStory(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#poststory) |
| `promoteChatMember` | `promoteChatMember(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#promotechatmember) |
| `readBusinessMessage` | `readBusinessMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#readbusinessmessage) |
| `refundStarPayment` | `refundStarPayment(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#refundstarpayment) |
| `removeBusinessAccountProfilePhoto` | `removeBusinessAccountProfilePhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#removebusinessaccountprofilephoto) |
| `removeChatVerification` | `removeChatVerification(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#removechatverification) |
| `removeMyProfilePhoto` | `removeMyProfilePhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#removemyprofilephoto) |
| `removeUserVerification` | `removeUserVerification(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#removeuserverification) |
| `reopenForumTopic` | `reopenForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#reopenforumtopic) |
| `reopenGeneralForumTopic` | `reopenGeneralForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#reopengeneralforumtopic) |
| `replaceManagedBotToken` | `replaceManagedBotToken(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#replacemanagedbottoken) |
| `replaceStickerInSet` | `replaceStickerInSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#replacestickerinset) |
| `repostStory` | `repostStory(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#repoststory) |
| `restrictChatMember` | `restrictChatMember(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#restrictchatmember) |
| `revokeChatInviteLink` | `revokeChatInviteLink(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#revokechatinvitelink) |
| `savePreparedInlineMessage` | `savePreparedInlineMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#savepreparedinlinemessage) |
| `savePreparedKeyboardButton` | `savePreparedKeyboardButton(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#savepreparedkeyboardbutton) |
| `sendAnimation` | `sendAnimation(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendanimation) |
| `sendAudio` | `sendAudio(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendaudio) |
| `sendChatAction` | `sendChatAction(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendchataction) |
| `sendChecklist` | `sendChecklist(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendchecklist) |
| `sendContact` | `sendContact(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendcontact) |
| `sendDice` | `sendDice(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#senddice) |
| `sendDocument` | `sendDocument(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#senddocument) |
| `sendGame` | `sendGame(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendgame) |
| `sendGift` | `sendGift(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendgift) |
| `sendInvoice` | `sendInvoice(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendinvoice) |
| `sendLivePhoto` | `sendLivePhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendlivephoto) |
| `sendLocation` | `sendLocation(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendlocation) |
| `sendMediaGroup` | `sendMediaGroup(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendmediagroup) |
| `sendMessage` | `sendMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendmessage) |
| `sendMessageDraft` | `sendMessageDraft(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendmessagedraft) |
| `sendPaidMedia` | `sendPaidMedia(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendpaidmedia) |
| `sendPhoto` | `sendPhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendphoto) |
| `sendPoll` | `sendPoll(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendpoll) |
| `sendSticker` | `sendSticker(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendsticker) |
| `sendVenue` | `sendVenue(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendvenue) |
| `sendVideo` | `sendVideo(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendvideo) |
| `sendVideoNote` | `sendVideoNote(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendvideonote) |
| `sendVoice` | `sendVoice(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#sendvoice) |
| `setBusinessAccountBio` | `setBusinessAccountBio(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountbio) |
| `setBusinessAccountGiftSettings` | `setBusinessAccountGiftSettings(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountgiftsettings) |
| `setBusinessAccountName` | `setBusinessAccountName(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountname) |
| `setBusinessAccountProfilePhoto` | `setBusinessAccountProfilePhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountprofilephoto) |
| `setBusinessAccountUsername` | `setBusinessAccountUsername(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountusername) |
| `setChatAdministratorCustomTitle` | `setChatAdministratorCustomTitle(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatadministratorcustomtitle) |
| `setChatDescription` | `setChatDescription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatdescription) |
| `setChatMemberTag` | `setChatMemberTag(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatmembertag) |
| `setChatMenuButton` | `setChatMenuButton(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatmenubutton) |
| `setChatPermissions` | `setChatPermissions(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatpermissions) |
| `setChatPhoto` | `setChatPhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatphoto) |
| `setChatStickerSet` | `setChatStickerSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchatstickerset) |
| `setChatTitle` | `setChatTitle(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setchattitle) |
| `setCustomEmojiStickerSetThumbnail` | `setCustomEmojiStickerSetThumbnail(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail) |
| `setGameScore` | `setGameScore(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setgamescore) |
| `setManagedBotAccessSettings` | `setManagedBotAccessSettings(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmanagedbotaccesssettings) |
| `setMessageReaction` | `setMessageReaction(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmessagereaction) |
| `setMyCommands` | `setMyCommands(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmycommands) |
| `setMyDefaultAdministratorRights` | `setMyDefaultAdministratorRights(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmydefaultadministratorrights) |
| `setMyDescription` | `setMyDescription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmydescription) |
| `setMyName` | `setMyName(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmyname) |
| `setMyProfilePhoto` | `setMyProfilePhoto(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmyprofilephoto) |
| `setMyShortDescription` | `setMyShortDescription(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setmyshortdescription) |
| `setPassportDataErrors` | `setPassportDataErrors(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setpassportdataerrors) |
| `setStickerEmojiList` | `setStickerEmojiList(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setstickeremojilist) |
| `setStickerKeywords` | `setStickerKeywords(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setstickerkeywords) |
| `setStickerMaskPosition` | `setStickerMaskPosition(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setstickermaskposition) |
| `setStickerPositionInSet` | `setStickerPositionInSet(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setstickerpositioninset) |
| `setStickerSetThumbnail` | `setStickerSetThumbnail(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setstickersetthumbnail) |
| `setStickerSetTitle` | `setStickerSetTitle(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setstickersettitle) |
| `setUserEmojiStatus` | `setUserEmojiStatus(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setuseremojistatus) |
| `setWebhook` | `setWebhook(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#setwebhook) |
| `stopMessageLiveLocation` | `stopMessageLiveLocation(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#stopmessagelivelocation) |
| `stopPoll` | `stopPoll(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#stoppoll) |
| `transferBusinessAccountStars` | `transferBusinessAccountStars(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#transferbusinessaccountstars) |
| `transferGift` | `transferGift(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#transfergift) |
| `unbanChatMember` | `unbanChatMember(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unbanchatmember) |
| `unbanChatSenderChat` | `unbanChatSenderChat(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unbanchatsenderchat) |
| `unhideGeneralForumTopic` | `unhideGeneralForumTopic(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unhidegeneralforumtopic) |
| `unpinAllChatMessages` | `unpinAllChatMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unpinallchatmessages) |
| `unpinAllForumTopicMessages` | `unpinAllForumTopicMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unpinallforumtopicmessages) |
| `unpinAllGeneralForumTopicMessages` | `unpinAllGeneralForumTopicMessages(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unpinallgeneralforumtopicmessages) |
| `unpinChatMessage` | `unpinChatMessage(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#unpinchatmessage) |
| `upgradeGift` | `upgradeGift(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#upgradegift) |
| `uploadStickerFile` | `uploadStickerFile(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#uploadstickerfile) |
| `verifyChat` | `verifyChat(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#verifychat) |
| `verifyUser` | `verifyUser(array $parameters = [])` | [Telegram docs](https://core.telegram.org/bots/api#verifyuser) |
