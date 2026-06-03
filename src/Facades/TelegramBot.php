<?php

namespace AlexItDev91\LaravelTelegramBot\Facades;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\Testing\TelegramBotFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed call(string $method, array $parameters = [])
 * @method static mixed callData(string $method, array $parameters = [])
 * @method static TelegramBotClient bot(?string $name = null)
 * @method static TelegramBotChannel channel(string $name)
 * @method static mixed getMe(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static \AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData getMeData()
 * @method static mixed sendMessage(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static \AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData sendMessageData(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static mixed sendDocument(array|\AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = [])
 *
 * @see TelegramBotManager
 */
class TelegramBot extends Facade
{
    public static function fake(?TelegramBotFake $fake = null): TelegramBotFake
    {
        $fake ??= new TelegramBotFake();

        static::swap($fake);

        app()->instance('telegram-bot', $fake);
        app()->instance(TelegramBotManagerContract::class, $fake);
        app()->instance(TelegramBotClientContract::class, $fake);

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'telegram-bot';
    }
}
