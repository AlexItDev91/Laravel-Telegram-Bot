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
        $this->assertStringContainsString('https://core.telegram.org/bots/api', $guidelines);
        $this->assertStringContainsString('https://core.telegram.org/bots/api-changelog', $guidelines);
    }

    public function test_package_ships_laravel_boost_skill(): void
    {
        $skill = file_get_contents(__DIR__.'/../../resources/boost/skills/telegram-bot-package/SKILL.md');

        $this->assertIsString($skill);
        $this->assertStringContainsString('name: telegram-bot-package', $skill);
        $this->assertStringContainsString('composer require alexitdev91/laravel-telegram-bot', $skill);
        $this->assertStringContainsString('vendor:publish --provider="AlexItDev91\\\\LaravelTelegramBot\\\\Laravel\\\\TelegramBotServiceProvider" --tag=telegram-bot-config', $skill);
        $this->assertStringContainsString('TelegramBot::channel', $skill);
    }
}
