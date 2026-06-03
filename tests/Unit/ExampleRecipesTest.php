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
            $root.'/examples/laravel/app/Telegram/Commands/BuyCommand.php',
            $root.'/examples/laravel/app/Telegram/Handlers/CallbackQueryHandler.php',
            $root.'/examples/laravel/app/Telegram/Handlers/ProfileWizardHandler.php',
            $root.'/examples/laravel/app/Telegram/Middleware/EnsureTelegramWebhookEnabled.php',
            $root.'/examples/laravel/app/Notifications/TelegramDeployFinished.php',
            $root.'/examples/laravel/app/Jobs/SendTelegramAlert.php',
            $root.'/examples/laravel/app/Listeners/RecordTelegramWebhookMetric.php',
            $root.'/examples/laravel/routes/telegram.php',
            $root.'/examples/laravel/tests/Feature/TelegramBotExampleTest.php',
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
        $payment = file_get_contents($root.'/examples/laravel/app/Telegram/Commands/BuyCommand.php');
        $handler = file_get_contents($root.'/examples/laravel/app/Telegram/Handlers/CallbackQueryHandler.php');
        $conversation = file_get_contents($root.'/examples/laravel/app/Telegram/Handlers/ProfileWizardHandler.php');
        $middleware = file_get_contents($root.'/examples/laravel/app/Telegram/Middleware/EnsureTelegramWebhookEnabled.php');
        $notification = file_get_contents($root.'/examples/laravel/app/Notifications/TelegramDeployFinished.php');
        $exampleTest = file_get_contents($root.'/examples/laravel/tests/Feature/TelegramBotExampleTest.php');
        $route = file_get_contents($root.'/examples/laravel/routes/telegram.php');

        $this->assertIsString($job);
        $this->assertIsString($listener);
        $this->assertIsString($command);
        $this->assertIsString($payment);
        $this->assertIsString($handler);
        $this->assertIsString($conversation);
        $this->assertIsString($middleware);
        $this->assertIsString($notification);
        $this->assertIsString($exampleTest);
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
        $this->assertStringContainsString('SendInvoiceData', $payment);
        $this->assertStringContainsString('TelegramConversationTransition', $conversation);
        $this->assertStringContainsString('TelegramWebhookMiddleware', $middleware);
        $this->assertStringContainsString('AnswerCallbackQueryData', $handler);
        $this->assertStringContainsString('TelegramBotNotificationChannel', $notification);
        $this->assertStringContainsString('TelegramNotificationMessage', $notification);
        $this->assertStringContainsString('assertSentTypedPayload', $exampleTest);
        $this->assertStringContainsString('Route::telegramBotWebhook', $route);
    }
}
