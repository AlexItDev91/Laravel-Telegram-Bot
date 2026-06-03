<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleRecipesTest extends TestCase
{
    public function test_laravel_examples_cover_production_recipes_without_committed_secrets(): void
    {
        $root = dirname(__DIR__, 2);
        $examples = [
            $root.'/examples/laravel/README.md',
            $root.'/examples/laravel/app/Telegram/Commands/StartCommand.php',
            $root.'/examples/laravel/app/Telegram/Handlers/CallbackQueryHandler.php',
            $root.'/examples/laravel/app/Notifications/TelegramDeployFinished.php',
            $root.'/examples/laravel/app/Jobs/SendTelegramAlert.php',
            $root.'/examples/laravel/app/Listeners/RecordTelegramWebhookMetric.php',
            $root.'/examples/laravel/routes/telegram.php',
        ];

        foreach ($examples as $example) {
            $this->assertFileExists($example);
            $contents = file_get_contents($example);
            $this->assertIsString($contents);
            $this->assertStringNotContainsString('123456:ABC', $contents);
            $this->assertStringNotContainsString('TELEGRAM_BOT_TOKEN=', $contents);
            $this->assertStringNotContainsString('TELEGRAM_WEBHOOK_SECRET_TOKEN=', $contents);
        }

        $job = file_get_contents($root.'/examples/laravel/app/Jobs/SendTelegramAlert.php');
        $listener = file_get_contents($root.'/examples/laravel/app/Listeners/RecordTelegramWebhookMetric.php');
        $command = file_get_contents($root.'/examples/laravel/app/Telegram/Commands/StartCommand.php');
        $handler = file_get_contents($root.'/examples/laravel/app/Telegram/Handlers/CallbackQueryHandler.php');
        $notification = file_get_contents($root.'/examples/laravel/app/Notifications/TelegramDeployFinished.php');
        $route = file_get_contents($root.'/examples/laravel/routes/telegram.php');

        $this->assertIsString($job);
        $this->assertIsString($listener);
        $this->assertIsString($command);
        $this->assertIsString($handler);
        $this->assertIsString($notification);
        $this->assertIsString($route);

        foreach ([
            'retryAfter()',
            'migrateToChatId()',
            '$this->release($exception->retryAfter())',
        ] as $requiredJobText) {
            $this->assertStringContainsString($requiredJobText, $job);
        }

        foreach ([
            'TelegramWebhookReceived',
            'TelegramWebhookHandled',
            'TelegramWebhookFailed',
            'TelegramWebhookQueued',
            'TelegramWebhookDuplicateSkipped',
        ] as $requiredListenerText) {
            $this->assertStringContainsString($requiredListenerText, $listener);
        }

        $this->assertStringContainsString('TelegramWebhookCommandHandler', $command);
        $this->assertStringContainsString('SendMessageData', $command);
        $this->assertStringContainsString('AnswerCallbackQueryData', $handler);
        $this->assertStringContainsString('TelegramBotNotificationChannel', $notification);
        $this->assertStringContainsString('TelegramNotificationMessage', $notification);
        $this->assertStringContainsString('Route::telegramBotWebhook', $route);
    }
}
