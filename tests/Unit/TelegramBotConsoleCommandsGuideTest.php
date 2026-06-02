<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class TelegramBotConsoleCommandsGuideTest extends TestCase
{
    public function test_console_command_guide_documents_artisan_workflows(): void
    {
        $guide = file_get_contents(__DIR__.'/../../docs/CONSOLE_COMMANDS.md');
        $readme = file_get_contents(__DIR__.'/../../README.md');

        $this->assertIsString($guide);
        $this->assertIsString($readme);
        $this->assertStringContainsString('[docs/CONSOLE_COMMANDS.md](docs/CONSOLE_COMMANDS.md)', $readme);

        foreach ([
            'telegram-bot:install',
            'telegram-bot:webhook:set',
            'telegram-bot:webhook:delete',
            'telegram-bot:webhook:info',
            'telegram-bot:updates',
            'Laravel Prompts',
            'chat_id',
            'message_thread_id',
            'direct_messages_topic_id',
            'TELEGRAM_CHAT_ID=-1009007199254740991',
            'TELEGRAM_MESSAGE_THREAD_ID=42',
            '--delete-webhook',
            '--raw',
        ] as $requiredInstruction) {
            $this->assertStringContainsString($requiredInstruction, $guide);
        }
    }
}
