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
        $this->assertStringContainsString('telegram-bot:doctor', $guidelines);
        $this->assertStringContainsString('telegram-bot:me', $guidelines);
        $this->assertStringContainsString('telegram-bot:updates', $guidelines);
        $this->assertStringContainsString('telegram-bot:send-test', $guidelines);
        $this->assertStringContainsString('telegram-bot:webhook:set', $guidelines);
        $this->assertStringContainsString('https://core.telegram.org/bots/api', $guidelines);
        $this->assertStringContainsString('https://core.telegram.org/bots/api-changelog', $guidelines);
        $this->assertStringContainsString('TelegramWebhookReceived', $guidelines);
        $this->assertStringContainsString('TelegramWebhookCommandHandler', $guidelines);
        $this->assertStringContainsString('TelegramWebhookCommand', $guidelines);
        $this->assertStringContainsString('TelegramBotNotificationChannel', $guidelines);
        $this->assertStringContainsString('TelegramNotificationMessage', $guidelines);
        $this->assertStringContainsString('routeNotificationForTelegram()', $guidelines);
        $this->assertStringContainsString('getMeData()', $guidelines);
        $this->assertStringContainsString('sendMessageData()', $guidelines);
        $this->assertStringContainsString('getWebhookInfoData()', $guidelines);
        $this->assertStringContainsString('TelegramBotResultData', $guidelines);
        $this->assertStringContainsString('TelegramBotRequestData::forMethod()', $guidelines);
        $this->assertStringContainsString('TelegramBotApiMethodSchema', $guidelines);
        $this->assertStringContainsString('Route::telegramBotWebhook()', $guidelines);
        $this->assertStringContainsString('TelegramBot::fake()', $guidelines);
        $this->assertStringContainsString('assertSentMessage()', $guidelines);
        $this->assertStringContainsString('effectiveMessage()', $guidelines);
        $this->assertStringContainsString('callbackQuery()', $guidelines);
        $this->assertStringContainsString('preCheckoutQueryData()', $guidelines);
        $this->assertStringContainsString('businessConnection()', $guidelines);
        $this->assertStringContainsString('poll()', $guidelines);
        $this->assertStringContainsString('messageReaction()', $guidelines);
        $this->assertStringContainsString('chatBoost()', $guidelines);
        $this->assertStringContainsString('managedBot()', $guidelines);
        $this->assertStringContainsString('documentData()', $guidelines);
        $this->assertStringContainsString('successfulPaymentData()', $guidelines);
        $this->assertStringContainsString('newChatMemberData()', $guidelines);
        $this->assertStringContainsString('retryAfter()', $guidelines);
        $this->assertStringContainsString('migrateToChatId()', $guidelines);
        $this->assertStringContainsString('TelegramWebhookMiddleware', $guidelines);
        $this->assertStringContainsString('telegram-bot.webhook.middleware', $guidelines);
        $this->assertStringContainsString('TelegramConversationManager', $guidelines);
        $this->assertStringContainsString('TELEGRAM_CONVERSATION_ENABLED', $guidelines);
        $this->assertStringContainsString('composer generate:telegram-api-schema', $guidelines);
    }

    public function test_package_ships_laravel_boost_skill(): void
    {
        $skill = file_get_contents(__DIR__.'/../../resources/boost/skills/telegram-bot-package/SKILL.md');

        $this->assertIsString($skill);
        $this->assertStringContainsString('name: telegram-bot-package', $skill);
        $this->assertStringContainsString('composer require alexitdev91/laravel-telegram-bot', $skill);
        $this->assertStringContainsString('vendor:publish --provider="AlexItDev91\\\\LaravelTelegramBot\\\\Laravel\\\\TelegramBotServiceProvider" --tag=telegram-bot-config', $skill);
        $this->assertStringContainsString('telegram-bot:install', $skill);
        $this->assertStringContainsString('telegram-bot:doctor', $skill);
        $this->assertStringContainsString('telegram-bot:me', $skill);
        $this->assertStringContainsString('telegram-bot:updates', $skill);
        $this->assertStringContainsString('telegram-bot:send-test', $skill);
        $this->assertStringContainsString('telegram-bot:webhook:set', $skill);
        $this->assertStringContainsString('TelegramBot::channel', $skill);
        $this->assertStringContainsString('TelegramBotNotificationChannel', $skill);
        $this->assertStringContainsString('TelegramNotificationMessage', $skill);
        $this->assertStringContainsString("Notification::route('telegram'", $skill);
        $this->assertStringContainsString('callData()', $skill);
        $this->assertStringContainsString('TelegramBotResultData', $skill);
        $this->assertStringContainsString('TelegramBotRequestData::forMethod()', $skill);
        $this->assertStringContainsString('TelegramBotApiMethodSchema', $skill);
        $this->assertStringContainsString('getUpdatesData()', $skill);
        $this->assertStringContainsString('sendMessageData()', $skill);
        $this->assertStringContainsString('SendMessageData', $skill);
        $this->assertStringContainsString('AnswerCallbackQueryData', $skill);
        $this->assertStringContainsString('TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID', $skill);
        $this->assertStringContainsString('TELEGRAM_WEBHOOK_SECRET_TOKEN', $skill);
        $this->assertStringContainsString('TELEGRAM_WEBHOOK_REQUIRE_SECRET', $skill);
        $this->assertStringContainsString('TELEGRAM_WEBHOOK_BOT_USERNAME', $skill);
        $this->assertStringContainsString('TELEGRAM_CONVERSATION_ENABLED', $skill);
        $this->assertStringContainsString('TELEGRAM_BOT_LOGGING_ENABLED', $skill);
        $this->assertStringContainsString('TelegramWebhookCommandHandler', $skill);
        $this->assertStringContainsString('TelegramWebhookCommand', $skill);
        $this->assertStringContainsString('TelegramWebhookHandled', $skill);
        $this->assertStringContainsString('TelegramWebhookDuplicateSkipped', $skill);
        $this->assertStringContainsString('TelegramWebhookMiddleware', $skill);
        $this->assertStringContainsString('telegram-bot.webhook.middleware', $skill);
        $this->assertStringContainsString('TelegramConversationManager', $skill);
        $this->assertStringContainsString('docs/RECIPES.md', $skill);
        $this->assertStringContainsString('docs/NOTIFICATIONS.md', $skill);
        $this->assertStringContainsString('docs/RESPONSES.md', $skill);
        $this->assertStringContainsString('examples/laravel', $skill);
        $this->assertStringContainsString('Route::telegramBotWebhook()', $skill);
        $this->assertStringContainsString('TelegramBot::fake()', $skill);
        $this->assertStringContainsString('assertSentMessage()', $skill);
        $this->assertStringContainsString('effectiveMessage()', $skill);
        $this->assertStringContainsString('callbackQuery()', $skill);
        $this->assertStringContainsString('preCheckoutQueryData()', $skill);
        $this->assertStringContainsString('businessConnection()', $skill);
        $this->assertStringContainsString('poll()', $skill);
        $this->assertStringContainsString('messageReaction()', $skill);
        $this->assertStringContainsString('chatBoost()', $skill);
        $this->assertStringContainsString('managedBot()', $skill);
        $this->assertStringContainsString('orderInfoData()', $skill);
        $this->assertStringContainsString('documentData()', $skill);
        $this->assertStringContainsString('successfulPaymentData()', $skill);
        $this->assertStringContainsString('newChatMemberData()', $skill);
        $this->assertStringContainsString('retryAfter()', $skill);
        $this->assertStringContainsString('migrateToChatId()', $skill);
        $this->assertStringContainsString('composer generate:telegram-api-schema', $skill);
    }
}
