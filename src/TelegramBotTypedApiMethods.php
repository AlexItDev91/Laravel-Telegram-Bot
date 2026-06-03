<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatFullInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatMemberData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramFileData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

trait TelegramBotTypedApiMethods
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    abstract public function callData(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed;

    public function getMeData(): TelegramUserData
    {
        return $this->callData(TelegramBotApiMethod::getMe);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatData(array|TelegramBotRequestData $parameters = []): TelegramChatFullInfoData
    {
        return $this->callData(TelegramBotApiMethod::getChat, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getChatMemberData(array|TelegramBotRequestData $parameters = []): TelegramChatMemberData
    {
        return $this->callData(TelegramBotApiMethod::getChatMember, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return list<TelegramChatMemberData>
     */
    public function getChatAdministratorsData(array|TelegramBotRequestData $parameters = []): array
    {
        return $this->callData(TelegramBotApiMethod::getChatAdministrators, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function getFileData(array|TelegramBotRequestData $parameters = []): TelegramFileData
    {
        return $this->callData(TelegramBotApiMethod::getFile, $parameters);
    }

    public function getWebhookInfoData(): TelegramWebhookInfoData
    {
        return $this->callData(TelegramBotApiMethod::getWebhookInfo);
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return list<TelegramWebhookUpdate>
     */
    public function getUpdatesData(array|TelegramBotRequestData $parameters = []): array
    {
        return $this->callData(TelegramBotApiMethod::getUpdates, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendMessageData(array|TelegramBotRequestData $parameters = []): TelegramMessageData
    {
        return $this->callData(TelegramBotApiMethod::sendMessage, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendPhotoData(array|TelegramBotRequestData $parameters = []): TelegramMessageData
    {
        return $this->callData(TelegramBotApiMethod::sendPhoto, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function sendDocumentData(array|TelegramBotRequestData $parameters = []): TelegramMessageData
    {
        return $this->callData(TelegramBotApiMethod::sendDocument, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function editMessageTextData(array|TelegramBotRequestData $parameters = []): TelegramMessageData|bool
    {
        return $this->callData(TelegramBotApiMethod::editMessageText, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function forwardMessageData(array|TelegramBotRequestData $parameters = []): TelegramMessageData
    {
        return $this->callData(TelegramBotApiMethod::forwardMessage, $parameters);
    }
}
