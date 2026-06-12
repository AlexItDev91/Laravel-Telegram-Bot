<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

trait TelegramBotApiMethods
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public function addStickerToSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::addStickerToSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerCallbackQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerCallbackQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerChatJoinRequestQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerChatJoinRequestQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerGuestQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerGuestQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerInlineQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerInlineQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerPreCheckoutQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerPreCheckoutQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerShippingQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerShippingQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function answerWebAppQuery(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::answerWebAppQuery, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function approveChatJoinRequest(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::approveChatJoinRequest, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function approveSuggestedPost(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::approveSuggestedPost, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function banChatMember(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::banChatMember, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function banChatSenderChat(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::banChatSenderChat, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function close(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::close, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function closeForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::closeForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function closeGeneralForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::closeGeneralForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function convertGiftToStars(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::convertGiftToStars, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function copyMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::copyMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function copyMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::copyMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function createChatInviteLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::createChatInviteLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function createChatSubscriptionInviteLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::createChatSubscriptionInviteLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function createForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::createForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function createInvoiceLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::createInvoiceLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function createNewStickerSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::createNewStickerSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function declineChatJoinRequest(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::declineChatJoinRequest, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function declineSuggestedPost(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::declineSuggestedPost, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteAllMessageReactions(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteAllMessageReactions, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteBusinessMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteBusinessMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteChatPhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteChatPhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteChatStickerSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteChatStickerSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteMessageReaction(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteMessageReaction, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteMyCommands(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteMyCommands, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteStickerFromSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteStickerFromSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteStickerSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteStickerSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteStory(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteStory, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function deleteWebhook(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::deleteWebhook, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editChatInviteLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editChatInviteLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editChatSubscriptionInviteLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editChatSubscriptionInviteLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editGeneralForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editGeneralForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageCaption(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editMessageCaption, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageChecklist(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editMessageChecklist, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageLiveLocation(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editMessageLiveLocation, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageMedia(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editMessageMedia, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageReplyMarkup(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editMessageReplyMarkup, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageText(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editMessageText, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editStory(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editStory, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editUserStarSubscription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::editUserStarSubscription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function exportChatInviteLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::exportChatInviteLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function forwardMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::forwardMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function forwardMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::forwardMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getAvailableGifts(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getAvailableGifts, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getBusinessAccountGifts(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getBusinessAccountGifts, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getBusinessAccountStarBalance(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getBusinessAccountStarBalance, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getBusinessConnection(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getBusinessConnection, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChat(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getChat, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatAdministrators(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getChatAdministrators, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatGifts(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getChatGifts, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatMember(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getChatMember, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatMemberCount(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getChatMemberCount, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatMenuButton(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getChatMenuButton, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getCustomEmojiStickers(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getCustomEmojiStickers, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getFile(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getFile, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getForumTopicIconStickers(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getForumTopicIconStickers, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getGameHighScores(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getGameHighScores, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getManagedBotAccessSettings(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getManagedBotAccessSettings, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getManagedBotToken(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getManagedBotToken, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMe(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMe, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMyCommands(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMyCommands, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMyDefaultAdministratorRights(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMyDefaultAdministratorRights, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMyDescription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMyDescription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMyName(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMyName, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMyShortDescription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMyShortDescription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getMyStarBalance(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getMyStarBalance, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getStarTransactions(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getStarTransactions, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getStickerSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getStickerSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getUpdates(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getUpdates, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getUserChatBoosts(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getUserChatBoosts, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getUserGifts(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getUserGifts, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getUserPersonalChatMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getUserPersonalChatMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getUserProfileAudios(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getUserProfileAudios, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getUserProfilePhotos(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getUserProfilePhotos, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getWebhookInfo(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::getWebhookInfo, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function giftPremiumSubscription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::giftPremiumSubscription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function hideGeneralForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::hideGeneralForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function leaveChat(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::leaveChat, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function logOut(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::logOut, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function pinChatMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::pinChatMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function postStory(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::postStory, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function promoteChatMember(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::promoteChatMember, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function readBusinessMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::readBusinessMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function refundStarPayment(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::refundStarPayment, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function removeBusinessAccountProfilePhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::removeBusinessAccountProfilePhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function removeChatVerification(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::removeChatVerification, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function removeMyProfilePhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::removeMyProfilePhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function removeUserVerification(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::removeUserVerification, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function reopenForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::reopenForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function reopenGeneralForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::reopenGeneralForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function replaceManagedBotToken(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::replaceManagedBotToken, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function replaceStickerInSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::replaceStickerInSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function repostStory(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::repostStory, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function restrictChatMember(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::restrictChatMember, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function revokeChatInviteLink(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::revokeChatInviteLink, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function savePreparedInlineMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::savePreparedInlineMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function savePreparedKeyboardButton(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::savePreparedKeyboardButton, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendAnimation(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendAnimation, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendAudio(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendAudio, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendChatAction(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendChatAction, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendChatJoinRequestWebApp(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendChatJoinRequestWebApp, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendChecklist(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendChecklist, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendContact(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendContact, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendDice(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendDice, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendDocument(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendDocument, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendGame(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendGame, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendGift(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendGift, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendInvoice(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendInvoice, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendLivePhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendLivePhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendLocation(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendLocation, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendMediaGroup(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendMediaGroup, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendMessageDraft(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendMessageDraft, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendPaidMedia(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendPaidMedia, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendPhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendPhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendPoll(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendPoll, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendRichMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendRichMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendRichMessageDraft(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendRichMessageDraft, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendSticker(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendSticker, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendVenue(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendVenue, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendVideo(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendVideo, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendVideoNote(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendVideoNote, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendVoice(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::sendVoice, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setBusinessAccountBio(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setBusinessAccountBio, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setBusinessAccountGiftSettings(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setBusinessAccountGiftSettings, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setBusinessAccountName(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setBusinessAccountName, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setBusinessAccountProfilePhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setBusinessAccountProfilePhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setBusinessAccountUsername(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setBusinessAccountUsername, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatAdministratorCustomTitle(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatAdministratorCustomTitle, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatDescription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatDescription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatMemberTag(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatMemberTag, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatMenuButton(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatMenuButton, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatPermissions(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatPermissions, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatPhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatPhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatStickerSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatStickerSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setChatTitle(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setChatTitle, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setCustomEmojiStickerSetThumbnail(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setCustomEmojiStickerSetThumbnail, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setGameScore(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setGameScore, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setManagedBotAccessSettings(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setManagedBotAccessSettings, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMessageReaction(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMessageReaction, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMyCommands(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMyCommands, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMyDefaultAdministratorRights(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMyDefaultAdministratorRights, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMyDescription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMyDescription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMyName(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMyName, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMyProfilePhoto(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMyProfilePhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setMyShortDescription(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setMyShortDescription, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setPassportDataErrors(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setPassportDataErrors, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setStickerEmojiList(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setStickerEmojiList, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setStickerKeywords(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setStickerKeywords, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setStickerMaskPosition(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setStickerMaskPosition, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setStickerPositionInSet(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setStickerPositionInSet, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setStickerSetThumbnail(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setStickerSetThumbnail, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setStickerSetTitle(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setStickerSetTitle, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setUserEmojiStatus(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setUserEmojiStatus, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function setWebhook(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::setWebhook, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function stopMessageLiveLocation(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::stopMessageLiveLocation, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function stopPoll(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::stopPoll, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function transferBusinessAccountStars(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::transferBusinessAccountStars, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function transferGift(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::transferGift, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unbanChatMember(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unbanChatMember, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unbanChatSenderChat(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unbanChatSenderChat, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unhideGeneralForumTopic(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unhideGeneralForumTopic, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unpinAllChatMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unpinAllChatMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unpinAllForumTopicMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unpinAllForumTopicMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unpinAllGeneralForumTopicMessages(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unpinAllGeneralForumTopicMessages, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function unpinChatMessage(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::unpinChatMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function upgradeGift(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::upgradeGift, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function uploadStickerFile(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::uploadStickerFile, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function verifyChat(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::verifyChat, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function verifyUser(array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->call(TelegramBotApiMethod::verifyUser, $parameters);
    }
}
