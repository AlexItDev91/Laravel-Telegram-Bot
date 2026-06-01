<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;

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
