<?php

namespace AlexItDev91\LaravelTelegramBot\Facades;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed call(string $method, array $parameters = [])
 * @method static TelegramBotClient bot(?string $name = null)
 * @method static TelegramBotChannel channel(string $name)
 * @method static mixed getMe(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static mixed sendMessage(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static mixed sendDocument(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 *
 * @see TelegramBotManager
 */
class TelegramBot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'telegram-bot';
    }
}
