<?php

namespace AlexItDev91\LaravelTelegramBot\Support;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatMemberData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramFileData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

final class TelegramBotResultFactory
{
    /**
     * @return mixed
     */
    public static function from(string|TelegramBotApiMethod $method, mixed $result): mixed
    {
        if ($result instanceof TelegramBotData) {
            return $result;
        }

        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;

        return match ($methodName) {
            TelegramBotApiMethod::getMe->value => self::user($result),
            TelegramBotApiMethod::getChat->value => self::chat($result),
            TelegramBotApiMethod::getFile->value => self::file($result),
            TelegramBotApiMethod::getWebhookInfo->value => self::webhookInfo($result),
            TelegramBotApiMethod::getUpdates->value => self::updates($result),
            TelegramBotApiMethod::getChatMember->value => self::chatMember($result),
            TelegramBotApiMethod::getChatAdministrators->value => self::chatMembers($result),
            TelegramBotApiMethod::editMessageText->value,
            TelegramBotApiMethod::editMessageCaption->value,
            TelegramBotApiMethod::editMessageMedia->value,
            TelegramBotApiMethod::editMessageReplyMarkup->value,
            TelegramBotApiMethod::editMessageLiveLocation->value,
            TelegramBotApiMethod::stopMessageLiveLocation->value => self::messageOrBool($result),
            TelegramBotApiMethod::sendMediaGroup->value => self::messages($result),
            TelegramBotApiMethod::forwardMessage->value,
            TelegramBotApiMethod::sendAnimation->value,
            TelegramBotApiMethod::sendAudio->value,
            TelegramBotApiMethod::sendContact->value,
            TelegramBotApiMethod::sendDice->value,
            TelegramBotApiMethod::sendDocument->value,
            TelegramBotApiMethod::sendGame->value,
            TelegramBotApiMethod::sendInvoice->value,
            TelegramBotApiMethod::sendLivePhoto->value,
            TelegramBotApiMethod::sendLocation->value,
            TelegramBotApiMethod::sendMessage->value,
            TelegramBotApiMethod::sendPaidMedia->value,
            TelegramBotApiMethod::sendPhoto->value,
            TelegramBotApiMethod::sendPoll->value,
            TelegramBotApiMethod::sendSticker->value,
            TelegramBotApiMethod::sendVenue->value,
            TelegramBotApiMethod::sendVideo->value,
            TelegramBotApiMethod::sendVideoNote->value,
            TelegramBotApiMethod::sendVoice->value,
            TelegramBotApiMethod::stopPoll->value => self::message($result),
            default => $result,
        };
    }

    private static function user(mixed $result): mixed
    {
        return is_array($result) ? TelegramUserData::fromPayload($result) : $result;
    }

    private static function chat(mixed $result): mixed
    {
        return is_array($result) ? TelegramChatData::fromPayload($result) : $result;
    }

    private static function chatMember(mixed $result): mixed
    {
        return is_array($result) ? TelegramChatMemberData::fromPayload($result) : $result;
    }

    private static function file(mixed $result): mixed
    {
        return is_array($result) ? TelegramFileData::fromPayload($result) : $result;
    }

    private static function webhookInfo(mixed $result): mixed
    {
        return is_array($result) ? TelegramWebhookInfoData::fromPayload($result) : $result;
    }

    private static function message(mixed $result): mixed
    {
        return is_array($result) ? TelegramMessageData::fromPayload($result) : $result;
    }

    private static function messageOrBool(mixed $result): mixed
    {
        return is_bool($result) ? $result : self::message($result);
    }

    /**
     * @return mixed
     */
    private static function updates(mixed $result): mixed
    {
        return self::mapList($result, static fn (array $payload): TelegramWebhookUpdate => TelegramWebhookUpdate::fromPayload($payload));
    }

    /**
     * @return mixed
     */
    private static function messages(mixed $result): mixed
    {
        return self::mapList($result, static fn (array $payload): TelegramMessageData => TelegramMessageData::fromPayload($payload));
    }

    /**
     * @return mixed
     */
    private static function chatMembers(mixed $result): mixed
    {
        return self::mapList($result, static fn (array $payload): TelegramChatMemberData => TelegramChatMemberData::fromPayload($payload));
    }

    /**
     * @param  callable(array<string, mixed>): TelegramBotData  $mapper
     * @return mixed
     */
    private static function mapList(mixed $result, callable $mapper): mixed
    {
        if (! is_array($result)) {
            return $result;
        }

        return array_map(
            static fn (array $payload): TelegramBotData => $mapper($payload),
            array_values(array_filter($result, static fn (mixed $item): bool => is_array($item))),
        );
    }
}
