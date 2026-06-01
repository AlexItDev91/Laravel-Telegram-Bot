<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class TelegramBotWebhookGuideTest extends TestCase
{
    public function test_webhook_guide_covers_laravel_receiver_setup(): void
    {
        $guide = file_get_contents(__DIR__.'/../../docs/WEBHOOKS.md');
        $readme = file_get_contents(__DIR__.'/../../README.md');

        $this->assertIsString($guide);
        $this->assertIsString($readme);
        $this->assertStringContainsString('[docs/WEBHOOKS.md](docs/WEBHOOKS.md)', $readme);

        foreach ([
            'TelegramWebhookHandler',
            'TelegramWebhookReceived',
            'TelegramWebhookUpdate',
            'X-Telegram-Bot-Api-Secret-Token',
            'TELEGRAM_WEBHOOK_SECRET_TOKEN',
            'route(\'telegram-bot.webhook\')',
            'setWebhook',
            'getWebhookInfo',
            'deleteWebhook',
            'Security Checklist',
        ] as $requiredInstruction) {
            $this->assertStringContainsString($requiredInstruction, $guide);
        }
    }
}
