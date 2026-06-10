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
        $this->assertStringContainsString('[Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html)', $readme);

        foreach ([
            'TelegramWebhookHandler',
            'TelegramWebhookCommandHandler',
            'TelegramWebhookCommand',
            'TelegramWebhookReceived',
            'TelegramWebhookUpdate',
            'effectiveMessage()',
            'effectiveChat()',
            'effectiveUser()',
            'callbackQuery()',
            'inlineQuery()',
            'shippingQueryData()',
            'preCheckoutQueryData()',
            'chatMember()',
            'businessConnection()',
            'deletedBusinessMessages()',
            'purchasedPaidMediaData()',
            'poll()',
            'pollAnswer()',
            'messageReaction()',
            'messageReactionCount()',
            'chatBoost()',
            'removedChatBoost()',
            'managedBot()',
            'photoData()',
            'documentData()',
            'entitiesData()',
            'successfulPaymentData()',
            'orderInfoData()',
            'newChatMemberData()',
            'Common Handler Patterns',
            'Dispatcher, Commands, And Fallbacks',
            'Route::telegramBotWebhook',
            'fallback_handler',
            'TELEGRAM_WEBHOOK_BOT_USERNAME',
            'X-Telegram-Bot-Api-Secret-Token',
            'TELEGRAM_WEBHOOK_SECRET_TOKEN',
            'TELEGRAM_WEBHOOK_REQUIRE_SECRET',
            'TELEGRAM_BOT_LOGGING_ENABLED',
            'TelegramHumanHandoff',
            'route(\'telegram-bot.webhook\')',
            'setWebhook',
            'getWebhookInfo',
            'deleteWebhook',
            'telegram-bot:webhook:set',
            'telegram-bot:webhook:info',
            'telegram-bot:webhook:delete',
            'docs/CONSOLE_COMMANDS.md',
            'Logging',
            'Security Checklist',
        ] as $requiredInstruction) {
            $this->assertStringContainsString($requiredInstruction, $guide);
        }
    }
}
