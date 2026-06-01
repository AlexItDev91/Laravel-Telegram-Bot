<?php

namespace Aptenova\TelegramBot\Facades;

use Aptenova\TelegramBot\Contracts\TelegramBotClient;
use Aptenova\TelegramBot\TelegramBotChannel;
use Aptenova\TelegramBot\TelegramBotManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed call(string $method, array $parameters = [])
 * @method static TelegramBotClient bot(?string $name = null)
 * @method static TelegramBotChannel channel(string $name)
 * @method static mixed getMe(array|\Aptenova\TelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static mixed sendMessage(array|\Aptenova\TelegramBot\DTO\TelegramBotRequestData $parameters = [])
 * @method static mixed sendDocument(array|\Aptenova\TelegramBot\DTO\TelegramBotRequestData $parameters = [])
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
