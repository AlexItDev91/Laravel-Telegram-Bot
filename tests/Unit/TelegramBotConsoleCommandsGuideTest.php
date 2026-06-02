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
        $this->assertStringContainsString('[Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html)', $readme);

        foreach ([
            'telegram-bot:install',
            'telegram-bot:me',
            'telegram-bot:send-test',
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
            'TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID=<direct-messages-topic-id-if-needed>',
            '--message-thread-id=42',
            '--direct-messages-topic-id=77',
            '--delete-webhook',
            '--raw',
        ] as $requiredInstruction) {
            $this->assertStringContainsString($requiredInstruction, $guide);
        }
    }
}
