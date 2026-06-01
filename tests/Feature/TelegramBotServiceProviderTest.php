<?php

namespace Aptenova\TelegramBot\Tests\Feature;

use Aptenova\TelegramBot\Contracts\TelegramBotClient;
use Aptenova\TelegramBot\Contracts\TelegramBotManager;
use Aptenova\TelegramBot\Facades\TelegramBot;
use Aptenova\TelegramBot\Tests\TestCase;

class TelegramBotServiceProviderTest extends TestCase
{
    public function test_registers_manager_client_and_facade(): void
    {
        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');

        $this->assertInstanceOf(TelegramBotManager::class, app('telegram-bot'));
        $this->assertInstanceOf(TelegramBotManager::class, TelegramBot::getFacadeRoot());
        $this->assertInstanceOf(TelegramBotClient::class, app(TelegramBotClient::class));
    }
}
