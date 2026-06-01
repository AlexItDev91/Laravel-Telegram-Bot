<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class TelegramBotSetupGuideTest extends TestCase
{
    public function test_setup_guide_covers_end_to_end_laravel_integration(): void
    {
        $guide = file_get_contents(__DIR__.'/../../docs/SETUP.md');
        $readme = file_get_contents(__DIR__.'/../../README.md');

        $this->assertIsString($guide);
        $this->assertIsString($readme);
        $this->assertStringContainsString('[docs/SETUP.md](docs/SETUP.md)', $readme);

        foreach ([
            'Create The Bot In BotFather',
            'Get The Bot ID And Username',
            'Create A Channel',
            'Add The Bot To The Channel',
            'Get The Channel Or Group ID With getUpdates',
            'Get A Channel ID With getChat',
            'Get A Topic ID',
            'Install The Laravel Package',
            'Configure Environment Values',
            'Configure config/telegram-bot.php',
            'Send A Test Message From Laravel',
            'Test With Tinker',
            'Troubleshooting',
            'Production Safety Checklist',
        ] as $section) {
            $this->assertStringContainsString($section, $guide);
        }

        foreach ([
            'https://api.telegram.org/bot<BOT_TOKEN>/getMe',
            'https://api.telegram.org/bot<BOT_TOKEN>/getUpdates',
            'https://api.telegram.org/bot<BOT_TOKEN>/getChat?chat_id=@company_inbox_alerts',
            'TELEGRAM_BOT_TOKEN',
            'TELEGRAM_INBOX_CHAT_ID',
            'TELEGRAM_INBOX_MESSAGE_THREAD_ID',
            'php artisan vendor:publish --provider="AlexItDev91\\\\LaravelTelegramBot\\\\Laravel\\\\TelegramBotServiceProvider" --tag=telegram-bot-config',
            'TelegramBot::channel(\'inbox\')->sendMessage',
            'TelegramBot::bot(\'support\')->sendMessage',
        ] as $requiredInstruction) {
            $this->assertStringContainsString($requiredInstruction, $guide);
        }
    }
}
