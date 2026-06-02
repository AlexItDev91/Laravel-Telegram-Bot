<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class LaravelBoostResourcesTest extends TestCase
{
    public function test_package_ships_laravel_boost_guidelines(): void
    {
        $guidelines = file_get_contents(__DIR__.'/../../resources/boost/guidelines/core.blade.php');

        $this->assertIsString($guidelines);
        $this->assertStringContainsString('alexitdev91/laravel-telegram-bot', $guidelines);
        $this->assertStringContainsString('vendor:publish --provider="AlexItDev91\\\\LaravelTelegramBot\\\\Laravel\\\\TelegramBotServiceProvider" --tag=telegram-bot-config', $guidelines);
        $this->assertStringContainsString('telegram-bot:install', $guidelines);
        $this->assertStringContainsString('telegram-bot:me', $guidelines);
        $this->assertStringContainsString('telegram-bot:updates', $guidelines);
        $this->assertStringContainsString('telegram-bot:send-test', $guidelines);
        $this->assertStringContainsString('telegram-bot:webhook:set', $guidelines);
        $this->assertStringContainsString('https://core.telegram.org/bots/api', $guidelines);
        $this->assertStringContainsString('https://core.telegram.org/bots/api-changelog', $guidelines);
        $this->assertStringContainsString('TelegramWebhookReceived', $guidelines);
    }

    public function test_package_ships_laravel_boost_skill(): void
    {
        $skill = file_get_contents(__DIR__.'/../../resources/boost/skills/telegram-bot-package/SKILL.md');

        $this->assertIsString($skill);
        $this->assertStringContainsString('name: telegram-bot-package', $skill);
        $this->assertStringContainsString('composer require alexitdev91/laravel-telegram-bot', $skill);
        $this->assertStringContainsString('vendor:publish --provider="AlexItDev91\\\\LaravelTelegramBot\\\\Laravel\\\\TelegramBotServiceProvider" --tag=telegram-bot-config', $skill);
        $this->assertStringContainsString('telegram-bot:install', $skill);
        $this->assertStringContainsString('telegram-bot:me', $skill);
        $this->assertStringContainsString('telegram-bot:updates', $skill);
        $this->assertStringContainsString('telegram-bot:send-test', $skill);
        $this->assertStringContainsString('telegram-bot:webhook:set', $skill);
        $this->assertStringContainsString('TelegramBot::channel', $skill);
        $this->assertStringContainsString('TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID', $skill);
        $this->assertStringContainsString('TELEGRAM_WEBHOOK_SECRET_TOKEN', $skill);
        $this->assertStringContainsString('TELEGRAM_WEBHOOK_REQUIRE_SECRET', $skill);
        $this->assertStringContainsString('TELEGRAM_BOT_LOGGING_ENABLED', $skill);
    }
}
