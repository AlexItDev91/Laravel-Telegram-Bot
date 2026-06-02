# API Method Reference

This document describes every Telegram Bot API method exposed by this package.

Package target: Telegram Bot API `10.0`, released on `2026-05-08`.

Primary sources:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Telegram Bot API changelog](https://core.telegram.org/bots/api-changelog)

## How SDK Methods Work

- Every Telegram method is exposed as a native PHP method on the client, manager, and facade forwarding surface.
- Every native method accepts `array|TelegramBotRequestData $parameters = []` and returns the decoded Telegram `result` value.
- The package sends JSON for normal requests and multipart form data when `InputFile` values are present, including nested media arrays that need Telegram `attach://` file references.
- The raw `call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = [])` method is always available for newly released Telegram methods.
- Telegram identifiers can exceed 32-bit integer range. Keep chat, user, message, and topic IDs as strings or 64-bit safe values.
- Type-hint concrete `TelegramBot` or `TelegramBotClient` when you want IDE autocomplete for every native helper method. The contracts expose the stable core manager/client surface.

## Common Call Shapes

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot as TelegramBotService;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\InputFile;

public function __construct(
    private TelegramBotService $telegram,
) {
}

$this->telegram->channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);

TelegramBot::bot('support')->sendMessage([
    'chat_id' => '-1001234567890',
    'text' => 'New message',
]);

TelegramBot::channel('inbox')->sendDocument([
    'document' => InputFile::fromPath(storage_path('app/report.pdf')),
    'caption' => 'Report',
]);

$this->telegram->bot('support')->sendMediaGroup([
    'chat_id' => '-1001234567890',
    'media' => [
        [
            'type' => 'photo',
            'media' => InputFile::fromPath(storage_path('app/photo.jpg')),
        ],
    ],
]);

TelegramBot::call('newTelegramMethod', [
    'parameter' => 'value',
]);
```

## Method Index

| Method | SDK call | API |
| --- | --- | --- |
| [`addStickerToSet`](#addstickertoset) | `addStickerToSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#addstickertoset) |
| [`answerCallbackQuery`](#answercallbackquery) | `answerCallbackQuery(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#answercallbackquery) |
| [`answerGuestQuery`](#answerguestquery) | `answerGuestQuery(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#answerguestquery) |
| [`answerInlineQuery`](#answerinlinequery) | `answerInlineQuery(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#answerinlinequery) |
| [`answerPreCheckoutQuery`](#answerprecheckoutquery) | `answerPreCheckoutQuery(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#answerprecheckoutquery) |
| [`answerShippingQuery`](#answershippingquery) | `answerShippingQuery(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#answershippingquery) |
| [`answerWebAppQuery`](#answerwebappquery) | `answerWebAppQuery(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#answerwebappquery) |
| [`approveChatJoinRequest`](#approvechatjoinrequest) | `approveChatJoinRequest(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#approvechatjoinrequest) |
| [`approveSuggestedPost`](#approvesuggestedpost) | `approveSuggestedPost(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#approvesuggestedpost) |
| [`banChatMember`](#banchatmember) | `banChatMember(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#banchatmember) |
| [`banChatSenderChat`](#banchatsenderchat) | `banChatSenderChat(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#banchatsenderchat) |
| [`close`](#close) | `close(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#close) |
| [`closeForumTopic`](#closeforumtopic) | `closeForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#closeforumtopic) |
| [`closeGeneralForumTopic`](#closegeneralforumtopic) | `closeGeneralForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#closegeneralforumtopic) |
| [`convertGiftToStars`](#convertgifttostars) | `convertGiftToStars(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#convertgifttostars) |
| [`copyMessage`](#copymessage) | `copyMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#copymessage) |
| [`copyMessages`](#copymessages) | `copyMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#copymessages) |
| [`createChatInviteLink`](#createchatinvitelink) | `createChatInviteLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#createchatinvitelink) |
| [`createChatSubscriptionInviteLink`](#createchatsubscriptioninvitelink) | `createChatSubscriptionInviteLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#createchatsubscriptioninvitelink) |
| [`createForumTopic`](#createforumtopic) | `createForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#createforumtopic) |
| [`createInvoiceLink`](#createinvoicelink) | `createInvoiceLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#createinvoicelink) |
| [`createNewStickerSet`](#createnewstickerset) | `createNewStickerSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#createnewstickerset) |
| [`declineChatJoinRequest`](#declinechatjoinrequest) | `declineChatJoinRequest(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#declinechatjoinrequest) |
| [`declineSuggestedPost`](#declinesuggestedpost) | `declineSuggestedPost(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#declinesuggestedpost) |
| [`deleteAllMessageReactions`](#deleteallmessagereactions) | `deleteAllMessageReactions(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deleteallmessagereactions) |
| [`deleteBusinessMessages`](#deletebusinessmessages) | `deleteBusinessMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletebusinessmessages) |
| [`deleteChatPhoto`](#deletechatphoto) | `deleteChatPhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletechatphoto) |
| [`deleteChatStickerSet`](#deletechatstickerset) | `deleteChatStickerSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletechatstickerset) |
| [`deleteForumTopic`](#deleteforumtopic) | `deleteForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deleteforumtopic) |
| [`deleteMessage`](#deletemessage) | `deleteMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletemessage) |
| [`deleteMessageReaction`](#deletemessagereaction) | `deleteMessageReaction(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletemessagereaction) |
| [`deleteMessages`](#deletemessages) | `deleteMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletemessages) |
| [`deleteMyCommands`](#deletemycommands) | `deleteMyCommands(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletemycommands) |
| [`deleteStickerFromSet`](#deletestickerfromset) | `deleteStickerFromSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletestickerfromset) |
| [`deleteStickerSet`](#deletestickerset) | `deleteStickerSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletestickerset) |
| [`deleteStory`](#deletestory) | `deleteStory(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletestory) |
| [`deleteWebhook`](#deletewebhook) | `deleteWebhook(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#deletewebhook) |
| [`editChatInviteLink`](#editchatinvitelink) | `editChatInviteLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editchatinvitelink) |
| [`editChatSubscriptionInviteLink`](#editchatsubscriptioninvitelink) | `editChatSubscriptionInviteLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editchatsubscriptioninvitelink) |
| [`editForumTopic`](#editforumtopic) | `editForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editforumtopic) |
| [`editGeneralForumTopic`](#editgeneralforumtopic) | `editGeneralForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editgeneralforumtopic) |
| [`editMessageCaption`](#editmessagecaption) | `editMessageCaption(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editmessagecaption) |
| [`editMessageChecklist`](#editmessagechecklist) | `editMessageChecklist(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editmessagechecklist) |
| [`editMessageLiveLocation`](#editmessagelivelocation) | `editMessageLiveLocation(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editmessagelivelocation) |
| [`editMessageMedia`](#editmessagemedia) | `editMessageMedia(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editmessagemedia) |
| [`editMessageReplyMarkup`](#editmessagereplymarkup) | `editMessageReplyMarkup(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editmessagereplymarkup) |
| [`editMessageText`](#editmessagetext) | `editMessageText(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editmessagetext) |
| [`editStory`](#editstory) | `editStory(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#editstory) |
| [`editUserStarSubscription`](#edituserstarsubscription) | `editUserStarSubscription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#edituserstarsubscription) |
| [`exportChatInviteLink`](#exportchatinvitelink) | `exportChatInviteLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#exportchatinvitelink) |
| [`forwardMessage`](#forwardmessage) | `forwardMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#forwardmessage) |
| [`forwardMessages`](#forwardmessages) | `forwardMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#forwardmessages) |
| [`getAvailableGifts`](#getavailablegifts) | `getAvailableGifts(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getavailablegifts) |
| [`getBusinessAccountGifts`](#getbusinessaccountgifts) | `getBusinessAccountGifts(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getbusinessaccountgifts) |
| [`getBusinessAccountStarBalance`](#getbusinessaccountstarbalance) | `getBusinessAccountStarBalance(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getbusinessaccountstarbalance) |
| [`getBusinessConnection`](#getbusinessconnection) | `getBusinessConnection(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getbusinessconnection) |
| [`getChat`](#getchat) | `getChat(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getchat) |
| [`getChatAdministrators`](#getchatadministrators) | `getChatAdministrators(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getchatadministrators) |
| [`getChatGifts`](#getchatgifts) | `getChatGifts(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getchatgifts) |
| [`getChatMember`](#getchatmember) | `getChatMember(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getchatmember) |
| [`getChatMemberCount`](#getchatmembercount) | `getChatMemberCount(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getchatmembercount) |
| [`getChatMenuButton`](#getchatmenubutton) | `getChatMenuButton(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getchatmenubutton) |
| [`getCustomEmojiStickers`](#getcustomemojistickers) | `getCustomEmojiStickers(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getcustomemojistickers) |
| [`getFile`](#getfile) | `getFile(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getfile) |
| [`getForumTopicIconStickers`](#getforumtopiciconstickers) | `getForumTopicIconStickers(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getforumtopiciconstickers) |
| [`getGameHighScores`](#getgamehighscores) | `getGameHighScores(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getgamehighscores) |
| [`getManagedBotAccessSettings`](#getmanagedbotaccesssettings) | `getManagedBotAccessSettings(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmanagedbotaccesssettings) |
| [`getManagedBotToken`](#getmanagedbottoken) | `getManagedBotToken(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmanagedbottoken) |
| [`getMe`](#getme) | `getMe(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getme) |
| [`getMyCommands`](#getmycommands) | `getMyCommands(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmycommands) |
| [`getMyDefaultAdministratorRights`](#getmydefaultadministratorrights) | `getMyDefaultAdministratorRights(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmydefaultadministratorrights) |
| [`getMyDescription`](#getmydescription) | `getMyDescription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmydescription) |
| [`getMyName`](#getmyname) | `getMyName(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmyname) |
| [`getMyShortDescription`](#getmyshortdescription) | `getMyShortDescription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmyshortdescription) |
| [`getMyStarBalance`](#getmystarbalance) | `getMyStarBalance(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getmystarbalance) |
| [`getStarTransactions`](#getstartransactions) | `getStarTransactions(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getstartransactions) |
| [`getStickerSet`](#getstickerset) | `getStickerSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getstickerset) |
| [`getUpdates`](#getupdates) | `getUpdates(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getupdates) |
| [`getUserChatBoosts`](#getuserchatboosts) | `getUserChatBoosts(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getuserchatboosts) |
| [`getUserGifts`](#getusergifts) | `getUserGifts(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getusergifts) |
| [`getUserPersonalChatMessages`](#getuserpersonalchatmessages) | `getUserPersonalChatMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getuserpersonalchatmessages) |
| [`getUserProfileAudios`](#getuserprofileaudios) | `getUserProfileAudios(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getuserprofileaudios) |
| [`getUserProfilePhotos`](#getuserprofilephotos) | `getUserProfilePhotos(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getuserprofilephotos) |
| [`getWebhookInfo`](#getwebhookinfo) | `getWebhookInfo(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#getwebhookinfo) |
| [`giftPremiumSubscription`](#giftpremiumsubscription) | `giftPremiumSubscription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#giftpremiumsubscription) |
| [`hideGeneralForumTopic`](#hidegeneralforumtopic) | `hideGeneralForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#hidegeneralforumtopic) |
| [`leaveChat`](#leavechat) | `leaveChat(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#leavechat) |
| [`logOut`](#logout) | `logOut(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#logout) |
| [`pinChatMessage`](#pinchatmessage) | `pinChatMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#pinchatmessage) |
| [`postStory`](#poststory) | `postStory(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#poststory) |
| [`promoteChatMember`](#promotechatmember) | `promoteChatMember(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#promotechatmember) |
| [`readBusinessMessage`](#readbusinessmessage) | `readBusinessMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#readbusinessmessage) |
| [`refundStarPayment`](#refundstarpayment) | `refundStarPayment(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#refundstarpayment) |
| [`removeBusinessAccountProfilePhoto`](#removebusinessaccountprofilephoto) | `removeBusinessAccountProfilePhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#removebusinessaccountprofilephoto) |
| [`removeChatVerification`](#removechatverification) | `removeChatVerification(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#removechatverification) |
| [`removeMyProfilePhoto`](#removemyprofilephoto) | `removeMyProfilePhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#removemyprofilephoto) |
| [`removeUserVerification`](#removeuserverification) | `removeUserVerification(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#removeuserverification) |
| [`reopenForumTopic`](#reopenforumtopic) | `reopenForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#reopenforumtopic) |
| [`reopenGeneralForumTopic`](#reopengeneralforumtopic) | `reopenGeneralForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#reopengeneralforumtopic) |
| [`replaceManagedBotToken`](#replacemanagedbottoken) | `replaceManagedBotToken(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#replacemanagedbottoken) |
| [`replaceStickerInSet`](#replacestickerinset) | `replaceStickerInSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#replacestickerinset) |
| [`repostStory`](#repoststory) | `repostStory(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#repoststory) |
| [`restrictChatMember`](#restrictchatmember) | `restrictChatMember(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#restrictchatmember) |
| [`revokeChatInviteLink`](#revokechatinvitelink) | `revokeChatInviteLink(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#revokechatinvitelink) |
| [`savePreparedInlineMessage`](#savepreparedinlinemessage) | `savePreparedInlineMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#savepreparedinlinemessage) |
| [`savePreparedKeyboardButton`](#savepreparedkeyboardbutton) | `savePreparedKeyboardButton(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#savepreparedkeyboardbutton) |
| [`sendAnimation`](#sendanimation) | `sendAnimation(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendanimation) |
| [`sendAudio`](#sendaudio) | `sendAudio(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendaudio) |
| [`sendChatAction`](#sendchataction) | `sendChatAction(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendchataction) |
| [`sendChecklist`](#sendchecklist) | `sendChecklist(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendchecklist) |
| [`sendContact`](#sendcontact) | `sendContact(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendcontact) |
| [`sendDice`](#senddice) | `sendDice(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#senddice) |
| [`sendDocument`](#senddocument) | `sendDocument(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#senddocument) |
| [`sendGame`](#sendgame) | `sendGame(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendgame) |
| [`sendGift`](#sendgift) | `sendGift(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendgift) |
| [`sendInvoice`](#sendinvoice) | `sendInvoice(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendinvoice) |
| [`sendLivePhoto`](#sendlivephoto) | `sendLivePhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendlivephoto) |
| [`sendLocation`](#sendlocation) | `sendLocation(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendlocation) |
| [`sendMediaGroup`](#sendmediagroup) | `sendMediaGroup(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendmediagroup) |
| [`sendMessage`](#sendmessage) | `sendMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendmessage) |
| [`sendMessageDraft`](#sendmessagedraft) | `sendMessageDraft(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendmessagedraft) |
| [`sendPaidMedia`](#sendpaidmedia) | `sendPaidMedia(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendpaidmedia) |
| [`sendPhoto`](#sendphoto) | `sendPhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendphoto) |
| [`sendPoll`](#sendpoll) | `sendPoll(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendpoll) |
| [`sendSticker`](#sendsticker) | `sendSticker(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendsticker) |
| [`sendVenue`](#sendvenue) | `sendVenue(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendvenue) |
| [`sendVideo`](#sendvideo) | `sendVideo(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendvideo) |
| [`sendVideoNote`](#sendvideonote) | `sendVideoNote(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendvideonote) |
| [`sendVoice`](#sendvoice) | `sendVoice(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#sendvoice) |
| [`setBusinessAccountBio`](#setbusinessaccountbio) | `setBusinessAccountBio(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setbusinessaccountbio) |
| [`setBusinessAccountGiftSettings`](#setbusinessaccountgiftsettings) | `setBusinessAccountGiftSettings(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setbusinessaccountgiftsettings) |
| [`setBusinessAccountName`](#setbusinessaccountname) | `setBusinessAccountName(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setbusinessaccountname) |
| [`setBusinessAccountProfilePhoto`](#setbusinessaccountprofilephoto) | `setBusinessAccountProfilePhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setbusinessaccountprofilephoto) |
| [`setBusinessAccountUsername`](#setbusinessaccountusername) | `setBusinessAccountUsername(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setbusinessaccountusername) |
| [`setChatAdministratorCustomTitle`](#setchatadministratorcustomtitle) | `setChatAdministratorCustomTitle(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatadministratorcustomtitle) |
| [`setChatDescription`](#setchatdescription) | `setChatDescription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatdescription) |
| [`setChatMemberTag`](#setchatmembertag) | `setChatMemberTag(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatmembertag) |
| [`setChatMenuButton`](#setchatmenubutton) | `setChatMenuButton(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatmenubutton) |
| [`setChatPermissions`](#setchatpermissions) | `setChatPermissions(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatpermissions) |
| [`setChatPhoto`](#setchatphoto) | `setChatPhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatphoto) |
| [`setChatStickerSet`](#setchatstickerset) | `setChatStickerSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchatstickerset) |
| [`setChatTitle`](#setchattitle) | `setChatTitle(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setchattitle) |
| [`setCustomEmojiStickerSetThumbnail`](#setcustomemojistickersetthumbnail) | `setCustomEmojiStickerSetThumbnail(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail) |
| [`setGameScore`](#setgamescore) | `setGameScore(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setgamescore) |
| [`setManagedBotAccessSettings`](#setmanagedbotaccesssettings) | `setManagedBotAccessSettings(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmanagedbotaccesssettings) |
| [`setMessageReaction`](#setmessagereaction) | `setMessageReaction(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmessagereaction) |
| [`setMyCommands`](#setmycommands) | `setMyCommands(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmycommands) |
| [`setMyDefaultAdministratorRights`](#setmydefaultadministratorrights) | `setMyDefaultAdministratorRights(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmydefaultadministratorrights) |
| [`setMyDescription`](#setmydescription) | `setMyDescription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmydescription) |
| [`setMyName`](#setmyname) | `setMyName(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmyname) |
| [`setMyProfilePhoto`](#setmyprofilephoto) | `setMyProfilePhoto(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmyprofilephoto) |
| [`setMyShortDescription`](#setmyshortdescription) | `setMyShortDescription(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setmyshortdescription) |
| [`setPassportDataErrors`](#setpassportdataerrors) | `setPassportDataErrors(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setpassportdataerrors) |
| [`setStickerEmojiList`](#setstickeremojilist) | `setStickerEmojiList(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setstickeremojilist) |
| [`setStickerKeywords`](#setstickerkeywords) | `setStickerKeywords(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setstickerkeywords) |
| [`setStickerMaskPosition`](#setstickermaskposition) | `setStickerMaskPosition(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setstickermaskposition) |
| [`setStickerPositionInSet`](#setstickerpositioninset) | `setStickerPositionInSet(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setstickerpositioninset) |
| [`setStickerSetThumbnail`](#setstickersetthumbnail) | `setStickerSetThumbnail(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setstickersetthumbnail) |
| [`setStickerSetTitle`](#setstickersettitle) | `setStickerSetTitle(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setstickersettitle) |
| [`setUserEmojiStatus`](#setuseremojistatus) | `setUserEmojiStatus(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setuseremojistatus) |
| [`setWebhook`](#setwebhook) | `setWebhook(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#setwebhook) |
| [`stopMessageLiveLocation`](#stopmessagelivelocation) | `stopMessageLiveLocation(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#stopmessagelivelocation) |
| [`stopPoll`](#stoppoll) | `stopPoll(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#stoppoll) |
| [`transferBusinessAccountStars`](#transferbusinessaccountstars) | `transferBusinessAccountStars(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#transferbusinessaccountstars) |
| [`transferGift`](#transfergift) | `transferGift(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#transfergift) |
| [`unbanChatMember`](#unbanchatmember) | `unbanChatMember(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unbanchatmember) |
| [`unbanChatSenderChat`](#unbanchatsenderchat) | `unbanChatSenderChat(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unbanchatsenderchat) |
| [`unhideGeneralForumTopic`](#unhidegeneralforumtopic) | `unhideGeneralForumTopic(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unhidegeneralforumtopic) |
| [`unpinAllChatMessages`](#unpinallchatmessages) | `unpinAllChatMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unpinallchatmessages) |
| [`unpinAllForumTopicMessages`](#unpinallforumtopicmessages) | `unpinAllForumTopicMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unpinallforumtopicmessages) |
| [`unpinAllGeneralForumTopicMessages`](#unpinallgeneralforumtopicmessages) | `unpinAllGeneralForumTopicMessages(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unpinallgeneralforumtopicmessages) |
| [`unpinChatMessage`](#unpinchatmessage) | `unpinChatMessage(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#unpinchatmessage) |
| [`upgradeGift`](#upgradegift) | `upgradeGift(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#upgradegift) |
| [`uploadStickerFile`](#uploadstickerfile) | `uploadStickerFile(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#uploadstickerfile) |
| [`verifyChat`](#verifychat) | `verifyChat(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#verifychat) |
| [`verifyUser`](#verifyuser) | `verifyUser(array\|TelegramBotRequestData $parameters = [])` | [API](https://core.telegram.org/bots/api#verifyuser) |

## Methods

### `addStickerToSet`

- SDK call: `addStickerToSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('addStickerToSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/addStickerToSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#addstickertoset)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `name` | `String` | `Yes` |
| `sticker` | `InputSticker` | `Yes` |

### `answerCallbackQuery`

- SDK call: `answerCallbackQuery(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('answerCallbackQuery', $parameters)`
- Endpoint: `POST /bot<TOKEN>/answerCallbackQuery`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#answercallbackquery)

| Parameter | Type | Required |
| --- | --- | --- |
| `callback_query_id` | `String` | `Yes` |
| `text` | `String` | `Optional` |
| `show_alert` | `Boolean` | `Optional` |
| `url` | `String` | `Optional` |
| `cache_time` | `Integer` | `Optional` |

### `answerGuestQuery`

- SDK call: `answerGuestQuery(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('answerGuestQuery', $parameters)`
- Endpoint: `POST /bot<TOKEN>/answerGuestQuery`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#answerguestquery)

| Parameter | Type | Required |
| --- | --- | --- |
| `guest_query_id` | `String` | `Yes` |
| `result` | `InlineQueryResult` | `Yes` |

### `answerInlineQuery`

- SDK call: `answerInlineQuery(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('answerInlineQuery', $parameters)`
- Endpoint: `POST /bot<TOKEN>/answerInlineQuery`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#answerinlinequery)

| Parameter | Type | Required |
| --- | --- | --- |
| `inline_query_id` | `String` | `Yes` |
| `results` | `Array of InlineQueryResult` | `Yes` |
| `cache_time` | `Integer` | `Optional` |
| `is_personal` | `Boolean` | `Optional` |
| `next_offset` | `String` | `Optional` |
| `button` | `InlineQueryResultsButton` | `Optional` |

### `answerPreCheckoutQuery`

- SDK call: `answerPreCheckoutQuery(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('answerPreCheckoutQuery', $parameters)`
- Endpoint: `POST /bot<TOKEN>/answerPreCheckoutQuery`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#answerprecheckoutquery)

| Parameter | Type | Required |
| --- | --- | --- |
| `pre_checkout_query_id` | `String` | `Yes` |
| `ok` | `Boolean` | `Yes` |
| `error_message` | `String` | `Optional` |

### `answerShippingQuery`

- SDK call: `answerShippingQuery(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('answerShippingQuery', $parameters)`
- Endpoint: `POST /bot<TOKEN>/answerShippingQuery`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#answershippingquery)

| Parameter | Type | Required |
| --- | --- | --- |
| `shipping_query_id` | `String` | `Yes` |
| `ok` | `Boolean` | `Yes` |
| `shipping_options` | `Array of ShippingOption` | `Optional` |
| `error_message` | `String` | `Optional` |

### `answerWebAppQuery`

- SDK call: `answerWebAppQuery(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('answerWebAppQuery', $parameters)`
- Endpoint: `POST /bot<TOKEN>/answerWebAppQuery`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#answerwebappquery)

| Parameter | Type | Required |
| --- | --- | --- |
| `web_app_query_id` | `String` | `Yes` |
| `result` | `InlineQueryResult` | `Yes` |

### `approveChatJoinRequest`

- SDK call: `approveChatJoinRequest(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('approveChatJoinRequest', $parameters)`
- Endpoint: `POST /bot<TOKEN>/approveChatJoinRequest`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#approvechatjoinrequest)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |

### `approveSuggestedPost`

- SDK call: `approveSuggestedPost(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('approveSuggestedPost', $parameters)`
- Endpoint: `POST /bot<TOKEN>/approveSuggestedPost`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#approvesuggestedpost)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `send_date` | `Integer` | `Optional` |

### `banChatMember`

- SDK call: `banChatMember(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('banChatMember', $parameters)`
- Endpoint: `POST /bot<TOKEN>/banChatMember`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#banchatmember)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `until_date` | `Integer` | `Optional` |
| `revoke_messages` | `Boolean` | `Optional` |

### `banChatSenderChat`

- SDK call: `banChatSenderChat(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('banChatSenderChat', $parameters)`
- Endpoint: `POST /bot<TOKEN>/banChatSenderChat`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#banchatsenderchat)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `sender_chat_id` | `Integer` | `Yes` |

### `close`

- SDK call: `close(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('close', $parameters)`
- Endpoint: `POST /bot<TOKEN>/close`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#close)

Parameters: none.

### `closeForumTopic`

- SDK call: `closeForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('closeForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/closeForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#closeforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Yes` |

### `closeGeneralForumTopic`

- SDK call: `closeGeneralForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('closeGeneralForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/closeGeneralForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#closegeneralforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `convertGiftToStars`

- SDK call: `convertGiftToStars(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('convertGiftToStars', $parameters)`
- Endpoint: `POST /bot<TOKEN>/convertGiftToStars`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#convertgifttostars)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `owned_gift_id` | `String` | `Yes` |

### `copyMessage`

- SDK call: `copyMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('copyMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/copyMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#copymessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `from_chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `video_start_timestamp` | `Integer` | `Optional` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `copyMessages`

- SDK call: `copyMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('copyMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/copyMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#copymessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `from_chat_id` | `Integer or String` | `Yes` |
| `message_ids` | `Array of Integer` | `Yes` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `remove_caption` | `Boolean` | `Optional` |

### `createChatInviteLink`

- SDK call: `createChatInviteLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('createChatInviteLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/createChatInviteLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#createchatinvitelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `name` | `String` | `Optional` |
| `expire_date` | `Integer` | `Optional` |
| `member_limit` | `Integer` | `Optional` |
| `creates_join_request` | `Boolean` | `Optional` |

### `createChatSubscriptionInviteLink`

- SDK call: `createChatSubscriptionInviteLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('createChatSubscriptionInviteLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/createChatSubscriptionInviteLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#createchatsubscriptioninvitelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `name` | `String` | `Optional` |
| `subscription_period` | `Integer` | `Yes` |
| `subscription_price` | `Integer` | `Yes` |

### `createForumTopic`

- SDK call: `createForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('createForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/createForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#createforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `name` | `String` | `Yes` |
| `icon_color` | `Integer` | `Optional` |
| `icon_custom_emoji_id` | `String` | `Optional` |

### `createInvoiceLink`

- SDK call: `createInvoiceLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('createInvoiceLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/createInvoiceLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#createinvoicelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `title` | `String` | `Yes` |
| `description` | `String` | `Yes` |
| `payload` | `String` | `Yes` |
| `provider_token` | `String` | `Optional` |
| `currency` | `String` | `Yes` |
| `prices` | `Array of LabeledPrice` | `Yes` |
| `subscription_period` | `Integer` | `Optional` |
| `max_tip_amount` | `Integer` | `Optional` |
| `suggested_tip_amounts` | `Array of Integer` | `Optional` |
| `provider_data` | `String` | `Optional` |
| `photo_url` | `String` | `Optional` |
| `photo_size` | `Integer` | `Optional` |
| `photo_width` | `Integer` | `Optional` |
| `photo_height` | `Integer` | `Optional` |
| `need_name` | `Boolean` | `Optional` |
| `need_phone_number` | `Boolean` | `Optional` |
| `need_email` | `Boolean` | `Optional` |
| `need_shipping_address` | `Boolean` | `Optional` |
| `send_phone_number_to_provider` | `Boolean` | `Optional` |
| `send_email_to_provider` | `Boolean` | `Optional` |
| `is_flexible` | `Boolean` | `Optional` |

### `createNewStickerSet`

- SDK call: `createNewStickerSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('createNewStickerSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/createNewStickerSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#createnewstickerset)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `name` | `String` | `Yes` |
| `title` | `String` | `Yes` |
| `stickers` | `Array of InputSticker` | `Yes` |
| `sticker_type` | `String` | `Optional` |
| `needs_repainting` | `Boolean` | `Optional` |

### `declineChatJoinRequest`

- SDK call: `declineChatJoinRequest(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('declineChatJoinRequest', $parameters)`
- Endpoint: `POST /bot<TOKEN>/declineChatJoinRequest`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#declinechatjoinrequest)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |

### `declineSuggestedPost`

- SDK call: `declineSuggestedPost(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('declineSuggestedPost', $parameters)`
- Endpoint: `POST /bot<TOKEN>/declineSuggestedPost`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#declinesuggestedpost)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `comment` | `String` | `Optional` |

### `deleteAllMessageReactions`

- SDK call: `deleteAllMessageReactions(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteAllMessageReactions', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteAllMessageReactions`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deleteallmessagereactions)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Optional` |
| `actor_chat_id` | `Integer` | `Optional` |

### `deleteBusinessMessages`

- SDK call: `deleteBusinessMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteBusinessMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteBusinessMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletebusinessmessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `message_ids` | `Array of Integer` | `Yes` |

### `deleteChatPhoto`

- SDK call: `deleteChatPhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteChatPhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteChatPhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletechatphoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `deleteChatStickerSet`

- SDK call: `deleteChatStickerSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteChatStickerSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteChatStickerSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletechatstickerset)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `deleteForumTopic`

- SDK call: `deleteForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deleteforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Yes` |

### `deleteMessage`

- SDK call: `deleteMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletemessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |

### `deleteMessageReaction`

- SDK call: `deleteMessageReaction(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteMessageReaction', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteMessageReaction`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletemessagereaction)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `user_id` | `Integer` | `Optional` |
| `actor_chat_id` | `Integer` | `Optional` |

### `deleteMessages`

- SDK call: `deleteMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletemessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_ids` | `Array of Integer` | `Yes` |

### `deleteMyCommands`

- SDK call: `deleteMyCommands(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteMyCommands', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteMyCommands`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletemycommands)

| Parameter | Type | Required |
| --- | --- | --- |
| `scope` | `BotCommandScope` | `Optional` |
| `language_code` | `String` | `Optional` |

### `deleteStickerFromSet`

- SDK call: `deleteStickerFromSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteStickerFromSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteStickerFromSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletestickerfromset)

| Parameter | Type | Required |
| --- | --- | --- |
| `sticker` | `String` | `Yes` |

### `deleteStickerSet`

- SDK call: `deleteStickerSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteStickerSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteStickerSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletestickerset)

| Parameter | Type | Required |
| --- | --- | --- |
| `name` | `String` | `Yes` |

### `deleteStory`

- SDK call: `deleteStory(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteStory', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteStory`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletestory)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `story_id` | `Integer` | `Yes` |

### `deleteWebhook`

- SDK call: `deleteWebhook(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('deleteWebhook', $parameters)`
- Endpoint: `POST /bot<TOKEN>/deleteWebhook`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#deletewebhook)

| Parameter | Type | Required |
| --- | --- | --- |
| `drop_pending_updates` | `Boolean` | `Optional` |

### `editChatInviteLink`

- SDK call: `editChatInviteLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editChatInviteLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editChatInviteLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editchatinvitelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `invite_link` | `String` | `Yes` |
| `name` | `String` | `Optional` |
| `expire_date` | `Integer` | `Optional` |
| `member_limit` | `Integer` | `Optional` |
| `creates_join_request` | `Boolean` | `Optional` |

### `editChatSubscriptionInviteLink`

- SDK call: `editChatSubscriptionInviteLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editChatSubscriptionInviteLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editChatSubscriptionInviteLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editchatsubscriptioninvitelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `invite_link` | `String` | `Yes` |
| `name` | `String` | `Optional` |

### `editForumTopic`

- SDK call: `editForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Yes` |
| `name` | `String` | `Optional` |
| `icon_custom_emoji_id` | `String` | `Optional` |

### `editGeneralForumTopic`

- SDK call: `editGeneralForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editGeneralForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editGeneralForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editgeneralforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `name` | `String` | `Yes` |

### `editMessageCaption`

- SDK call: `editMessageCaption(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editMessageCaption', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editMessageCaption`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editmessagecaption)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `editMessageChecklist`

- SDK call: `editMessageChecklist(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editMessageChecklist', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editMessageChecklist`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editmessagechecklist)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `checklist` | `InputChecklist` | `Yes` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `editMessageLiveLocation`

- SDK call: `editMessageLiveLocation(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editMessageLiveLocation', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editMessageLiveLocation`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editmessagelivelocation)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |
| `latitude` | `Float` | `Yes` |
| `longitude` | `Float` | `Yes` |
| `live_period` | `Integer` | `Optional` |
| `horizontal_accuracy` | `Float` | `Optional` |
| `heading` | `Integer` | `Optional` |
| `proximity_alert_radius` | `Integer` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `editMessageMedia`

- SDK call: `editMessageMedia(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editMessageMedia', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editMessageMedia`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editmessagemedia)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |
| `media` | `InputMedia` | `Yes` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `editMessageReplyMarkup`

- SDK call: `editMessageReplyMarkup(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editMessageReplyMarkup', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editMessageReplyMarkup`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editmessagereplymarkup)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `editMessageText`

- SDK call: `editMessageText(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editMessageText', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editMessageText`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editmessagetext)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |
| `text` | `String` | `Yes` |
| `parse_mode` | `String` | `Optional` |
| `entities` | `Array of MessageEntity` | `Optional` |
| `link_preview_options` | `LinkPreviewOptions` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `editStory`

- SDK call: `editStory(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editStory', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editStory`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#editstory)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `story_id` | `Integer` | `Yes` |
| `content` | `InputStoryContent` | `Yes` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `areas` | `Array of StoryArea` | `Optional` |

### `editUserStarSubscription`

- SDK call: `editUserStarSubscription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('editUserStarSubscription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/editUserStarSubscription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#edituserstarsubscription)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `telegram_payment_charge_id` | `String` | `Yes` |
| `is_canceled` | `Boolean` | `Yes` |

### `exportChatInviteLink`

- SDK call: `exportChatInviteLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('exportChatInviteLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/exportChatInviteLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#exportchatinvitelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `forwardMessage`

- SDK call: `forwardMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('forwardMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/forwardMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#forwardmessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `from_chat_id` | `Integer or String` | `Yes` |
| `video_start_timestamp` | `Integer` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `message_id` | `Integer` | `Yes` |

### `forwardMessages`

- SDK call: `forwardMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('forwardMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/forwardMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#forwardmessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `from_chat_id` | `Integer or String` | `Yes` |
| `message_ids` | `Array of Integer` | `Yes` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |

### `getAvailableGifts`

- SDK call: `getAvailableGifts(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getAvailableGifts', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getAvailableGifts`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getavailablegifts)

Parameters: none.

### `getBusinessAccountGifts`

- SDK call: `getBusinessAccountGifts(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getBusinessAccountGifts', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getBusinessAccountGifts`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getbusinessaccountgifts)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `exclude_unsaved` | `Boolean` | `Optional` |
| `exclude_saved` | `Boolean` | `Optional` |
| `exclude_unlimited` | `Boolean` | `Optional` |
| `exclude_limited_upgradable` | `Boolean` | `Optional` |
| `exclude_limited_non_upgradable` | `Boolean` | `Optional` |
| `exclude_unique` | `Boolean` | `Optional` |
| `exclude_from_blockchain` | `Boolean` | `Optional` |
| `sort_by_price` | `Boolean` | `Optional` |
| `offset` | `String` | `Optional` |
| `limit` | `Integer` | `Optional` |

### `getBusinessAccountStarBalance`

- SDK call: `getBusinessAccountStarBalance(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getBusinessAccountStarBalance', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getBusinessAccountStarBalance`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getbusinessaccountstarbalance)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |

### `getBusinessConnection`

- SDK call: `getBusinessConnection(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getBusinessConnection', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getBusinessConnection`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getbusinessconnection)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |

### `getChat`

- SDK call: `getChat(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getChat', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getChat`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getchat)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `getChatAdministrators`

- SDK call: `getChatAdministrators(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getChatAdministrators', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getChatAdministrators`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getchatadministrators)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `return_bots` | `Boolean` | `Optional` |

### `getChatGifts`

- SDK call: `getChatGifts(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getChatGifts', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getChatGifts`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getchatgifts)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `exclude_unsaved` | `Boolean` | `Optional` |
| `exclude_saved` | `Boolean` | `Optional` |
| `exclude_unlimited` | `Boolean` | `Optional` |
| `exclude_limited_upgradable` | `Boolean` | `Optional` |
| `exclude_limited_non_upgradable` | `Boolean` | `Optional` |
| `exclude_from_blockchain` | `Boolean` | `Optional` |
| `exclude_unique` | `Boolean` | `Optional` |
| `sort_by_price` | `Boolean` | `Optional` |
| `offset` | `String` | `Optional` |
| `limit` | `Integer` | `Optional` |

### `getChatMember`

- SDK call: `getChatMember(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getChatMember', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getChatMember`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getchatmember)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |

### `getChatMemberCount`

- SDK call: `getChatMemberCount(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getChatMemberCount', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getChatMemberCount`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getchatmembercount)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `getChatMenuButton`

- SDK call: `getChatMenuButton(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getChatMenuButton', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getChatMenuButton`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getchatmenubutton)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer` | `Optional` |

### `getCustomEmojiStickers`

- SDK call: `getCustomEmojiStickers(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getCustomEmojiStickers', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getCustomEmojiStickers`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getcustomemojistickers)

| Parameter | Type | Required |
| --- | --- | --- |
| `custom_emoji_ids` | `Array of String` | `Yes` |

### `getFile`

- SDK call: `getFile(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getFile', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getFile`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getfile)

| Parameter | Type | Required |
| --- | --- | --- |
| `file_id` | `String` | `Yes` |

### `getForumTopicIconStickers`

- SDK call: `getForumTopicIconStickers(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getForumTopicIconStickers', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getForumTopicIconStickers`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getforumtopiciconstickers)

Parameters: none.

### `getGameHighScores`

- SDK call: `getGameHighScores(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getGameHighScores', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getGameHighScores`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getgamehighscores)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `chat_id` | `Integer` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |

### `getManagedBotAccessSettings`

- SDK call: `getManagedBotAccessSettings(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getManagedBotAccessSettings', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getManagedBotAccessSettings`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmanagedbotaccesssettings)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |

### `getManagedBotToken`

- SDK call: `getManagedBotToken(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getManagedBotToken', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getManagedBotToken`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmanagedbottoken)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |

### `getMe`

- SDK call: `getMe(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMe', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMe`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getme)

Parameters: none.

### `getMyCommands`

- SDK call: `getMyCommands(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMyCommands', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMyCommands`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmycommands)

| Parameter | Type | Required |
| --- | --- | --- |
| `scope` | `BotCommandScope` | `Optional` |
| `language_code` | `String` | `Optional` |

### `getMyDefaultAdministratorRights`

- SDK call: `getMyDefaultAdministratorRights(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMyDefaultAdministratorRights', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMyDefaultAdministratorRights`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmydefaultadministratorrights)

| Parameter | Type | Required |
| --- | --- | --- |
| `for_channels` | `Boolean` | `Optional` |

### `getMyDescription`

- SDK call: `getMyDescription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMyDescription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMyDescription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmydescription)

| Parameter | Type | Required |
| --- | --- | --- |
| `language_code` | `String` | `Optional` |

### `getMyName`

- SDK call: `getMyName(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMyName', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMyName`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmyname)

| Parameter | Type | Required |
| --- | --- | --- |
| `language_code` | `String` | `Optional` |

### `getMyShortDescription`

- SDK call: `getMyShortDescription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMyShortDescription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMyShortDescription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmyshortdescription)

| Parameter | Type | Required |
| --- | --- | --- |
| `language_code` | `String` | `Optional` |

### `getMyStarBalance`

- SDK call: `getMyStarBalance(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getMyStarBalance', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getMyStarBalance`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getmystarbalance)

Parameters: none.

### `getStarTransactions`

- SDK call: `getStarTransactions(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getStarTransactions', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getStarTransactions`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getstartransactions)

| Parameter | Type | Required |
| --- | --- | --- |
| `offset` | `Integer` | `Optional` |
| `limit` | `Integer` | `Optional` |

### `getStickerSet`

- SDK call: `getStickerSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getStickerSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getStickerSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getstickerset)

| Parameter | Type | Required |
| --- | --- | --- |
| `name` | `String` | `Yes` |

### `getUpdates`

- SDK call: `getUpdates(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getUpdates', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getUpdates`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getupdates)

| Parameter | Type | Required |
| --- | --- | --- |
| `offset` | `Integer` | `Optional` |
| `limit` | `Integer` | `Optional` |
| `timeout` | `Integer` | `Optional` |
| `allowed_updates` | `Array of String` | `Optional` |

### `getUserChatBoosts`

- SDK call: `getUserChatBoosts(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getUserChatBoosts', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getUserChatBoosts`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getuserchatboosts)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |

### `getUserGifts`

- SDK call: `getUserGifts(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getUserGifts', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getUserGifts`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getusergifts)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `exclude_unlimited` | `Boolean` | `Optional` |
| `exclude_limited_upgradable` | `Boolean` | `Optional` |
| `exclude_limited_non_upgradable` | `Boolean` | `Optional` |
| `exclude_from_blockchain` | `Boolean` | `Optional` |
| `exclude_unique` | `Boolean` | `Optional` |
| `sort_by_price` | `Boolean` | `Optional` |
| `offset` | `String` | `Optional` |
| `limit` | `Integer` | `Optional` |

### `getUserPersonalChatMessages`

- SDK call: `getUserPersonalChatMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getUserPersonalChatMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getUserPersonalChatMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getuserpersonalchatmessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `limit` | `Integer` | `Yes` |

### `getUserProfileAudios`

- SDK call: `getUserProfileAudios(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getUserProfileAudios', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getUserProfileAudios`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getuserprofileaudios)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `offset` | `Integer` | `Optional` |
| `limit` | `Integer` | `Optional` |

### `getUserProfilePhotos`

- SDK call: `getUserProfilePhotos(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getUserProfilePhotos', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getUserProfilePhotos`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getuserprofilephotos)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `offset` | `Integer` | `Optional` |
| `limit` | `Integer` | `Optional` |

### `getWebhookInfo`

- SDK call: `getWebhookInfo(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('getWebhookInfo', $parameters)`
- Endpoint: `POST /bot<TOKEN>/getWebhookInfo`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#getwebhookinfo)

Parameters: none.

### `giftPremiumSubscription`

- SDK call: `giftPremiumSubscription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('giftPremiumSubscription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/giftPremiumSubscription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#giftpremiumsubscription)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `month_count` | `Integer` | `Yes` |
| `star_count` | `Integer` | `Yes` |
| `text` | `String` | `Optional` |
| `text_parse_mode` | `String` | `Optional` |
| `text_entities` | `Array of MessageEntity` | `Optional` |

### `hideGeneralForumTopic`

- SDK call: `hideGeneralForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('hideGeneralForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/hideGeneralForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#hidegeneralforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `leaveChat`

- SDK call: `leaveChat(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('leaveChat', $parameters)`
- Endpoint: `POST /bot<TOKEN>/leaveChat`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#leavechat)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `logOut`

- SDK call: `logOut(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('logOut', $parameters)`
- Endpoint: `POST /bot<TOKEN>/logOut`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#logout)

Parameters: none.

### `pinChatMessage`

- SDK call: `pinChatMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('pinChatMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/pinChatMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#pinchatmessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `disable_notification` | `Boolean` | `Optional` |

### `postStory`

- SDK call: `postStory(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('postStory', $parameters)`
- Endpoint: `POST /bot<TOKEN>/postStory`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#poststory)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `content` | `InputStoryContent` | `Yes` |
| `active_period` | `Integer` | `Yes` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `areas` | `Array of StoryArea` | `Optional` |
| `post_to_chat_page` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |

### `promoteChatMember`

- SDK call: `promoteChatMember(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('promoteChatMember', $parameters)`
- Endpoint: `POST /bot<TOKEN>/promoteChatMember`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#promotechatmember)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `is_anonymous` | `Boolean` | `Optional` |
| `can_manage_chat` | `Boolean` | `Optional` |
| `can_delete_messages` | `Boolean` | `Optional` |
| `can_manage_video_chats` | `Boolean` | `Optional` |
| `can_restrict_members` | `Boolean` | `Optional` |
| `can_promote_members` | `Boolean` | `Optional` |
| `can_change_info` | `Boolean` | `Optional` |
| `can_invite_users` | `Boolean` | `Optional` |
| `can_post_stories` | `Boolean` | `Optional` |
| `can_edit_stories` | `Boolean` | `Optional` |
| `can_delete_stories` | `Boolean` | `Optional` |
| `can_post_messages` | `Boolean` | `Optional` |
| `can_edit_messages` | `Boolean` | `Optional` |
| `can_pin_messages` | `Boolean` | `Optional` |
| `can_manage_topics` | `Boolean` | `Optional` |
| `can_manage_direct_messages` | `Boolean` | `Optional` |
| `can_manage_tags` | `Boolean` | `Optional` |

### `readBusinessMessage`

- SDK call: `readBusinessMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('readBusinessMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/readBusinessMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#readbusinessmessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `chat_id` | `Integer` | `Yes` |
| `message_id` | `Integer` | `Yes` |

### `refundStarPayment`

- SDK call: `refundStarPayment(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('refundStarPayment', $parameters)`
- Endpoint: `POST /bot<TOKEN>/refundStarPayment`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#refundstarpayment)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `telegram_payment_charge_id` | `String` | `Yes` |

### `removeBusinessAccountProfilePhoto`

- SDK call: `removeBusinessAccountProfilePhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('removeBusinessAccountProfilePhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/removeBusinessAccountProfilePhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#removebusinessaccountprofilephoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `is_public` | `Boolean` | `Optional` |

### `removeChatVerification`

- SDK call: `removeChatVerification(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('removeChatVerification', $parameters)`
- Endpoint: `POST /bot<TOKEN>/removeChatVerification`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#removechatverification)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `removeMyProfilePhoto`

- SDK call: `removeMyProfilePhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('removeMyProfilePhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/removeMyProfilePhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#removemyprofilephoto)

Parameters: none.

### `removeUserVerification`

- SDK call: `removeUserVerification(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('removeUserVerification', $parameters)`
- Endpoint: `POST /bot<TOKEN>/removeUserVerification`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#removeuserverification)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |

### `reopenForumTopic`

- SDK call: `reopenForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('reopenForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/reopenForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#reopenforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Yes` |

### `reopenGeneralForumTopic`

- SDK call: `reopenGeneralForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('reopenGeneralForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/reopenGeneralForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#reopengeneralforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `replaceManagedBotToken`

- SDK call: `replaceManagedBotToken(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('replaceManagedBotToken', $parameters)`
- Endpoint: `POST /bot<TOKEN>/replaceManagedBotToken`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#replacemanagedbottoken)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |

### `replaceStickerInSet`

- SDK call: `replaceStickerInSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('replaceStickerInSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/replaceStickerInSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#replacestickerinset)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `name` | `String` | `Yes` |
| `old_sticker` | `String` | `Yes` |
| `sticker` | `InputSticker` | `Yes` |

### `repostStory`

- SDK call: `repostStory(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('repostStory', $parameters)`
- Endpoint: `POST /bot<TOKEN>/repostStory`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#repoststory)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `from_chat_id` | `Integer` | `Yes` |
| `from_story_id` | `Integer` | `Yes` |
| `active_period` | `Integer` | `Yes` |
| `post_to_chat_page` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |

### `restrictChatMember`

- SDK call: `restrictChatMember(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('restrictChatMember', $parameters)`
- Endpoint: `POST /bot<TOKEN>/restrictChatMember`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#restrictchatmember)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `permissions` | `ChatPermissions` | `Yes` |
| `use_independent_chat_permissions` | `Boolean` | `Optional` |
| `until_date` | `Integer` | `Optional` |

### `revokeChatInviteLink`

- SDK call: `revokeChatInviteLink(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('revokeChatInviteLink', $parameters)`
- Endpoint: `POST /bot<TOKEN>/revokeChatInviteLink`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#revokechatinvitelink)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `invite_link` | `String` | `Yes` |

### `savePreparedInlineMessage`

- SDK call: `savePreparedInlineMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('savePreparedInlineMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/savePreparedInlineMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#savepreparedinlinemessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `result` | `InlineQueryResult` | `Yes` |
| `allow_user_chats` | `Boolean` | `Optional` |
| `allow_bot_chats` | `Boolean` | `Optional` |
| `allow_group_chats` | `Boolean` | `Optional` |
| `allow_channel_chats` | `Boolean` | `Optional` |

### `savePreparedKeyboardButton`

- SDK call: `savePreparedKeyboardButton(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('savePreparedKeyboardButton', $parameters)`
- Endpoint: `POST /bot<TOKEN>/savePreparedKeyboardButton`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#savepreparedkeyboardbutton)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `button` | `KeyboardButton` | `Yes` |

### `sendAnimation`

- SDK call: `sendAnimation(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendAnimation', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendAnimation`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendanimation)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `animation` | `InputFile or String` | `Yes` |
| `duration` | `Integer` | `Optional` |
| `width` | `Integer` | `Optional` |
| `height` | `Integer` | `Optional` |
| `thumbnail` | `InputFile or String` | `Optional` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `has_spoiler` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendAudio`

- SDK call: `sendAudio(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendAudio', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendAudio`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendaudio)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `audio` | `InputFile or String` | `Yes` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `duration` | `Integer` | `Optional` |
| `performer` | `String` | `Optional` |
| `title` | `String` | `Optional` |
| `thumbnail` | `InputFile or String` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendChatAction`

- SDK call: `sendChatAction(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendChatAction', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendChatAction`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendchataction)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `action` | `String` | `Yes` |

### `sendChecklist`

- SDK call: `sendChecklist(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendChecklist', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendChecklist`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendchecklist)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `chat_id` | `Integer or String` | `Yes` |
| `checklist` | `InputChecklist` | `Yes` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `sendContact`

- SDK call: `sendContact(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendContact', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendContact`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendcontact)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `phone_number` | `String` | `Yes` |
| `first_name` | `String` | `Yes` |
| `last_name` | `String` | `Optional` |
| `vcard` | `String` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendDice`

- SDK call: `sendDice(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendDice', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendDice`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#senddice)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `emoji` | `String` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendDocument`

- SDK call: `sendDocument(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendDocument', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendDocument`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#senddocument)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `document` | `InputFile or String` | `Yes` |
| `thumbnail` | `InputFile or String` | `Optional` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `disable_content_type_detection` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendGame`

- SDK call: `sendGame(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendGame', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendGame`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendgame)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `game_short_name` | `String` | `Yes` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `sendGift`

- SDK call: `sendGift(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendGift', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendGift`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendgift)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `gift_id` | `String` | `Yes` |
| `pay_for_upgrade` | `Boolean` | `Optional` |
| `text` | `String` | `Optional` |
| `text_parse_mode` | `String` | `Optional` |
| `text_entities` | `Array of MessageEntity` | `Optional` |

### `sendInvoice`

- SDK call: `sendInvoice(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendInvoice', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendInvoice`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendinvoice)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `title` | `String` | `Yes` |
| `description` | `String` | `Yes` |
| `payload` | `String` | `Yes` |
| `provider_token` | `String` | `Optional` |
| `currency` | `String` | `Yes` |
| `prices` | `Array of LabeledPrice` | `Yes` |
| `max_tip_amount` | `Integer` | `Optional` |
| `suggested_tip_amounts` | `Array of Integer` | `Optional` |
| `start_parameter` | `String` | `Optional` |
| `provider_data` | `String` | `Optional` |
| `photo_url` | `String` | `Optional` |
| `photo_size` | `Integer` | `Optional` |
| `photo_width` | `Integer` | `Optional` |
| `photo_height` | `Integer` | `Optional` |
| `need_name` | `Boolean` | `Optional` |
| `need_phone_number` | `Boolean` | `Optional` |
| `need_email` | `Boolean` | `Optional` |
| `need_shipping_address` | `Boolean` | `Optional` |
| `send_phone_number_to_provider` | `Boolean` | `Optional` |
| `send_email_to_provider` | `Boolean` | `Optional` |
| `is_flexible` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `sendLivePhoto`

- SDK call: `sendLivePhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendLivePhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendLivePhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendlivephoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `live_photo` | `InputFile or String` | `Yes` |
| `photo` | `InputFile or String` | `Yes` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `has_spoiler` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendLocation`

- SDK call: `sendLocation(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendLocation', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendLocation`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendlocation)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `latitude` | `Float` | `Yes` |
| `longitude` | `Float` | `Yes` |
| `horizontal_accuracy` | `Float` | `Optional` |
| `live_period` | `Integer` | `Optional` |
| `heading` | `Integer` | `Optional` |
| `proximity_alert_radius` | `Integer` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendMediaGroup`

- SDK call: `sendMediaGroup(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendMediaGroup', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendMediaGroup`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendmediagroup)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `media` | `Array of InputMediaAudio, InputMediaDocument, InputMediaLivePhoto, InputMediaPhoto and InputMediaVideo` | `Yes` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |

### `sendMessage`

- SDK call: `sendMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendmessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `text` | `String` | `Yes` |
| `parse_mode` | `String` | `Optional` |
| `entities` | `Array of MessageEntity` | `Optional` |
| `link_preview_options` | `LinkPreviewOptions` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendMessageDraft`

- SDK call: `sendMessageDraft(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendMessageDraft', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendMessageDraft`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendmessagedraft)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `draft_id` | `Integer` | `Yes` |
| `text` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `entities` | `Array of MessageEntity` | `Optional` |

### `sendPaidMedia`

- SDK call: `sendPaidMedia(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendPaidMedia', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendPaidMedia`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendpaidmedia)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `star_count` | `Integer` | `Yes` |
| `media` | `Array of InputPaidMedia` | `Yes` |
| `payload` | `String` | `Optional` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendPhoto`

- SDK call: `sendPhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendPhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendPhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendphoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `photo` | `InputFile or String` | `Yes` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `has_spoiler` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendPoll`

- SDK call: `sendPoll(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendPoll', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendPoll`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendpoll)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `question` | `String` | `Yes` |
| `question_parse_mode` | `String` | `Optional` |
| `question_entities` | `Array of MessageEntity` | `Optional` |
| `options` | `Array of InputPollOption` | `Yes` |
| `is_anonymous` | `Boolean` | `Optional` |
| `type` | `String` | `Optional` |
| `allows_multiple_answers` | `Boolean` | `Optional` |
| `allows_revoting` | `Boolean` | `Optional` |
| `shuffle_options` | `Boolean` | `Optional` |
| `allow_adding_options` | `Boolean` | `Optional` |
| `hide_results_until_closes` | `Boolean` | `Optional` |
| `members_only` | `Boolean` | `Optional` |
| `country_codes` | `Array of String` | `Optional` |
| `correct_option_ids` | `Array of Integer` | `Optional` |
| `explanation` | `String` | `Optional` |
| `explanation_parse_mode` | `String` | `Optional` |
| `explanation_entities` | `Array of MessageEntity` | `Optional` |
| `explanation_media` | `InputPollMedia` | `Optional` |
| `open_period` | `Integer` | `Optional` |
| `close_date` | `Integer` | `Optional` |
| `is_closed` | `Boolean` | `Optional` |
| `description` | `String` | `Optional` |
| `description_parse_mode` | `String` | `Optional` |
| `description_entities` | `Array of MessageEntity` | `Optional` |
| `media` | `InputPollMedia` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendSticker`

- SDK call: `sendSticker(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendSticker', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendSticker`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendsticker)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `sticker` | `InputFile or String` | `Yes` |
| `emoji` | `String` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendVenue`

- SDK call: `sendVenue(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendVenue', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendVenue`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendvenue)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `latitude` | `Float` | `Yes` |
| `longitude` | `Float` | `Yes` |
| `title` | `String` | `Yes` |
| `address` | `String` | `Yes` |
| `foursquare_id` | `String` | `Optional` |
| `foursquare_type` | `String` | `Optional` |
| `google_place_id` | `String` | `Optional` |
| `google_place_type` | `String` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendVideo`

- SDK call: `sendVideo(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendVideo', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendVideo`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendvideo)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `video` | `InputFile or String` | `Yes` |
| `duration` | `Integer` | `Optional` |
| `width` | `Integer` | `Optional` |
| `height` | `Integer` | `Optional` |
| `thumbnail` | `InputFile or String` | `Optional` |
| `cover` | `InputFile or String` | `Optional` |
| `start_timestamp` | `Integer` | `Optional` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `show_caption_above_media` | `Boolean` | `Optional` |
| `has_spoiler` | `Boolean` | `Optional` |
| `supports_streaming` | `Boolean` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendVideoNote`

- SDK call: `sendVideoNote(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendVideoNote', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendVideoNote`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendvideonote)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `video_note` | `InputFile or String` | `Yes` |
| `duration` | `Integer` | `Optional` |
| `length` | `Integer` | `Optional` |
| `thumbnail` | `InputFile or String` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `sendVoice`

- SDK call: `sendVoice(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('sendVoice', $parameters)`
- Endpoint: `POST /bot<TOKEN>/sendVoice`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#sendvoice)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Optional` |
| `direct_messages_topic_id` | `Integer` | `Optional` |
| `voice` | `InputFile or String` | `Yes` |
| `caption` | `String` | `Optional` |
| `parse_mode` | `String` | `Optional` |
| `caption_entities` | `Array of MessageEntity` | `Optional` |
| `duration` | `Integer` | `Optional` |
| `disable_notification` | `Boolean` | `Optional` |
| `protect_content` | `Boolean` | `Optional` |
| `allow_paid_broadcast` | `Boolean` | `Optional` |
| `message_effect_id` | `String` | `Optional` |
| `suggested_post_parameters` | `SuggestedPostParameters` | `Optional` |
| `reply_parameters` | `ReplyParameters` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply` | `Optional` |

### `setBusinessAccountBio`

- SDK call: `setBusinessAccountBio(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setBusinessAccountBio', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setBusinessAccountBio`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountbio)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `bio` | `String` | `Optional` |

### `setBusinessAccountGiftSettings`

- SDK call: `setBusinessAccountGiftSettings(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setBusinessAccountGiftSettings', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setBusinessAccountGiftSettings`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountgiftsettings)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `show_gift_button` | `Boolean` | `Yes` |
| `accepted_gift_types` | `AcceptedGiftTypes` | `Yes` |

### `setBusinessAccountName`

- SDK call: `setBusinessAccountName(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setBusinessAccountName', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setBusinessAccountName`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountname)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `first_name` | `String` | `Yes` |
| `last_name` | `String` | `Optional` |

### `setBusinessAccountProfilePhoto`

- SDK call: `setBusinessAccountProfilePhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setBusinessAccountProfilePhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setBusinessAccountProfilePhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountprofilephoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `photo` | `InputProfilePhoto` | `Yes` |
| `is_public` | `Boolean` | `Optional` |

### `setBusinessAccountUsername`

- SDK call: `setBusinessAccountUsername(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setBusinessAccountUsername', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setBusinessAccountUsername`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setbusinessaccountusername)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `username` | `String` | `Optional` |

### `setChatAdministratorCustomTitle`

- SDK call: `setChatAdministratorCustomTitle(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatAdministratorCustomTitle', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatAdministratorCustomTitle`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatadministratorcustomtitle)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `custom_title` | `String` | `Yes` |

### `setChatDescription`

- SDK call: `setChatDescription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatDescription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatDescription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatdescription)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `description` | `String` | `Optional` |

### `setChatMemberTag`

- SDK call: `setChatMemberTag(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatMemberTag', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatMemberTag`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatmembertag)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `tag` | `String` | `Optional` |

### `setChatMenuButton`

- SDK call: `setChatMenuButton(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatMenuButton', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatMenuButton`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatmenubutton)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer` | `Optional` |
| `menu_button` | `MenuButton` | `Optional` |

### `setChatPermissions`

- SDK call: `setChatPermissions(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatPermissions', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatPermissions`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatpermissions)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `permissions` | `ChatPermissions` | `Yes` |
| `use_independent_chat_permissions` | `Boolean` | `Optional` |

### `setChatPhoto`

- SDK call: `setChatPhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatPhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatPhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatphoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `photo` | `InputFile` | `Yes` |

### `setChatStickerSet`

- SDK call: `setChatStickerSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatStickerSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatStickerSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchatstickerset)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `sticker_set_name` | `String` | `Yes` |

### `setChatTitle`

- SDK call: `setChatTitle(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setChatTitle', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setChatTitle`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setchattitle)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `title` | `String` | `Yes` |

### `setCustomEmojiStickerSetThumbnail`

- SDK call: `setCustomEmojiStickerSetThumbnail(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setCustomEmojiStickerSetThumbnail', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setCustomEmojiStickerSetThumbnail`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail)

| Parameter | Type | Required |
| --- | --- | --- |
| `name` | `String` | `Yes` |
| `custom_emoji_id` | `String` | `Optional` |

### `setGameScore`

- SDK call: `setGameScore(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setGameScore', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setGameScore`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setgamescore)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `score` | `Integer` | `Yes` |
| `force` | `Boolean` | `Optional` |
| `disable_edit_message` | `Boolean` | `Optional` |
| `chat_id` | `Integer` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |

### `setManagedBotAccessSettings`

- SDK call: `setManagedBotAccessSettings(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setManagedBotAccessSettings', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setManagedBotAccessSettings`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmanagedbotaccesssettings)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `is_access_restricted` | `Boolean` | `Yes` |
| `added_user_ids` | `Array of Integer` | `Optional` |

### `setMessageReaction`

- SDK call: `setMessageReaction(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMessageReaction', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMessageReaction`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmessagereaction)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `reaction` | `Array of ReactionType` | `Optional` |
| `is_big` | `Boolean` | `Optional` |

### `setMyCommands`

- SDK call: `setMyCommands(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMyCommands', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMyCommands`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmycommands)

| Parameter | Type | Required |
| --- | --- | --- |
| `commands` | `Array of BotCommand` | `Yes` |
| `scope` | `BotCommandScope` | `Optional` |
| `language_code` | `String` | `Optional` |

### `setMyDefaultAdministratorRights`

- SDK call: `setMyDefaultAdministratorRights(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMyDefaultAdministratorRights', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMyDefaultAdministratorRights`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmydefaultadministratorrights)

| Parameter | Type | Required |
| --- | --- | --- |
| `rights` | `ChatAdministratorRights` | `Optional` |
| `for_channels` | `Boolean` | `Optional` |

### `setMyDescription`

- SDK call: `setMyDescription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMyDescription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMyDescription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmydescription)

| Parameter | Type | Required |
| --- | --- | --- |
| `description` | `String` | `Optional` |
| `language_code` | `String` | `Optional` |

### `setMyName`

- SDK call: `setMyName(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMyName', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMyName`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmyname)

| Parameter | Type | Required |
| --- | --- | --- |
| `name` | `String` | `Optional` |
| `language_code` | `String` | `Optional` |

### `setMyProfilePhoto`

- SDK call: `setMyProfilePhoto(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMyProfilePhoto', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMyProfilePhoto`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmyprofilephoto)

| Parameter | Type | Required |
| --- | --- | --- |
| `photo` | `InputProfilePhoto` | `Yes` |

### `setMyShortDescription`

- SDK call: `setMyShortDescription(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setMyShortDescription', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setMyShortDescription`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setmyshortdescription)

| Parameter | Type | Required |
| --- | --- | --- |
| `short_description` | `String` | `Optional` |
| `language_code` | `String` | `Optional` |

### `setPassportDataErrors`

- SDK call: `setPassportDataErrors(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setPassportDataErrors', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setPassportDataErrors`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setpassportdataerrors)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `errors` | `Array of PassportElementError` | `Yes` |

### `setStickerEmojiList`

- SDK call: `setStickerEmojiList(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setStickerEmojiList', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setStickerEmojiList`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setstickeremojilist)

| Parameter | Type | Required |
| --- | --- | --- |
| `sticker` | `String` | `Yes` |
| `emoji_list` | `Array of String` | `Yes` |

### `setStickerKeywords`

- SDK call: `setStickerKeywords(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setStickerKeywords', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setStickerKeywords`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setstickerkeywords)

| Parameter | Type | Required |
| --- | --- | --- |
| `sticker` | `String` | `Yes` |
| `keywords` | `Array of String` | `Optional` |

### `setStickerMaskPosition`

- SDK call: `setStickerMaskPosition(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setStickerMaskPosition', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setStickerMaskPosition`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setstickermaskposition)

| Parameter | Type | Required |
| --- | --- | --- |
| `sticker` | `String` | `Yes` |
| `mask_position` | `MaskPosition` | `Optional` |

### `setStickerPositionInSet`

- SDK call: `setStickerPositionInSet(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setStickerPositionInSet', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setStickerPositionInSet`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setstickerpositioninset)

| Parameter | Type | Required |
| --- | --- | --- |
| `sticker` | `String` | `Yes` |
| `position` | `Integer` | `Yes` |

### `setStickerSetThumbnail`

- SDK call: `setStickerSetThumbnail(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setStickerSetThumbnail', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setStickerSetThumbnail`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setstickersetthumbnail)

| Parameter | Type | Required |
| --- | --- | --- |
| `name` | `String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `thumbnail` | `InputFile or String` | `Optional` |
| `format` | `String` | `Yes` |

### `setStickerSetTitle`

- SDK call: `setStickerSetTitle(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setStickerSetTitle', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setStickerSetTitle`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setstickersettitle)

| Parameter | Type | Required |
| --- | --- | --- |
| `name` | `String` | `Yes` |
| `title` | `String` | `Yes` |

### `setUserEmojiStatus`

- SDK call: `setUserEmojiStatus(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setUserEmojiStatus', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setUserEmojiStatus`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setuseremojistatus)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `emoji_status_custom_emoji_id` | `String` | `Optional` |
| `emoji_status_expiration_date` | `Integer` | `Optional` |

### `setWebhook`

- SDK call: `setWebhook(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('setWebhook', $parameters)`
- Endpoint: `POST /bot<TOKEN>/setWebhook`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#setwebhook)

| Parameter | Type | Required |
| --- | --- | --- |
| `url` | `String` | `Yes` |
| `certificate` | `InputFile` | `Optional` |
| `ip_address` | `String` | `Optional` |
| `max_connections` | `Integer` | `Optional` |
| `allowed_updates` | `Array of String` | `Optional` |
| `drop_pending_updates` | `Boolean` | `Optional` |
| `secret_token` | `String` | `Optional` |

### `stopMessageLiveLocation`

- SDK call: `stopMessageLiveLocation(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('stopMessageLiveLocation', $parameters)`
- Endpoint: `POST /bot<TOKEN>/stopMessageLiveLocation`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#stopmessagelivelocation)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Optional` |
| `message_id` | `Integer` | `Optional` |
| `inline_message_id` | `String` | `Optional` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `stopPoll`

- SDK call: `stopPoll(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('stopPoll', $parameters)`
- Endpoint: `POST /bot<TOKEN>/stopPoll`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#stoppoll)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Yes` |
| `reply_markup` | `InlineKeyboardMarkup` | `Optional` |

### `transferBusinessAccountStars`

- SDK call: `transferBusinessAccountStars(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('transferBusinessAccountStars', $parameters)`
- Endpoint: `POST /bot<TOKEN>/transferBusinessAccountStars`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#transferbusinessaccountstars)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `star_count` | `Integer` | `Yes` |

### `transferGift`

- SDK call: `transferGift(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('transferGift', $parameters)`
- Endpoint: `POST /bot<TOKEN>/transferGift`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#transfergift)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `owned_gift_id` | `String` | `Yes` |
| `new_owner_chat_id` | `Integer` | `Yes` |
| `star_count` | `Integer` | `Optional` |

### `unbanChatMember`

- SDK call: `unbanChatMember(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unbanChatMember', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unbanChatMember`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unbanchatmember)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `user_id` | `Integer` | `Yes` |
| `only_if_banned` | `Boolean` | `Optional` |

### `unbanChatSenderChat`

- SDK call: `unbanChatSenderChat(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unbanChatSenderChat', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unbanChatSenderChat`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unbanchatsenderchat)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `sender_chat_id` | `Integer` | `Yes` |

### `unhideGeneralForumTopic`

- SDK call: `unhideGeneralForumTopic(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unhideGeneralForumTopic', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unhideGeneralForumTopic`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unhidegeneralforumtopic)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `unpinAllChatMessages`

- SDK call: `unpinAllChatMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unpinAllChatMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unpinAllChatMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unpinallchatmessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `unpinAllForumTopicMessages`

- SDK call: `unpinAllForumTopicMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unpinAllForumTopicMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unpinAllForumTopicMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unpinallforumtopicmessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `message_thread_id` | `Integer` | `Yes` |

### `unpinAllGeneralForumTopicMessages`

- SDK call: `unpinAllGeneralForumTopicMessages(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unpinAllGeneralForumTopicMessages', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unpinAllGeneralForumTopicMessages`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unpinallgeneralforumtopicmessages)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |

### `unpinChatMessage`

- SDK call: `unpinChatMessage(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('unpinChatMessage', $parameters)`
- Endpoint: `POST /bot<TOKEN>/unpinChatMessage`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#unpinchatmessage)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Optional` |
| `chat_id` | `Integer or String` | `Yes` |
| `message_id` | `Integer` | `Optional` |

### `upgradeGift`

- SDK call: `upgradeGift(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('upgradeGift', $parameters)`
- Endpoint: `POST /bot<TOKEN>/upgradeGift`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#upgradegift)

| Parameter | Type | Required |
| --- | --- | --- |
| `business_connection_id` | `String` | `Yes` |
| `owned_gift_id` | `String` | `Yes` |
| `keep_original_details` | `Boolean` | `Optional` |
| `star_count` | `Integer` | `Optional` |

### `uploadStickerFile`

- SDK call: `uploadStickerFile(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('uploadStickerFile', $parameters)`
- Endpoint: `POST /bot<TOKEN>/uploadStickerFile`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#uploadstickerfile)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `sticker` | `InputFile` | `Yes` |
| `sticker_format` | `String` | `Yes` |

### `verifyChat`

- SDK call: `verifyChat(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('verifyChat', $parameters)`
- Endpoint: `POST /bot<TOKEN>/verifyChat`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#verifychat)

| Parameter | Type | Required |
| --- | --- | --- |
| `chat_id` | `Integer or String` | `Yes` |
| `custom_description` | `String` | `Optional` |

### `verifyUser`

- SDK call: `verifyUser(array|TelegramBotRequestData $parameters = [])`
- Raw call: `call('verifyUser', $parameters)`
- Endpoint: `POST /bot<TOKEN>/verifyUser`
- Official source: [Telegram docs](https://core.telegram.org/bots/api#verifyuser)

| Parameter | Type | Required |
| --- | --- | --- |
| `user_id` | `Integer` | `Yes` |
| `custom_description` | `String` | `Optional` |
