<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class WritersideDocumentationTest extends TestCase
{
    public function test_writerside_project_and_github_pages_workflow_are_configured(): void
    {
        $root = dirname(__DIR__, 2);

        $config = file_get_contents($root.'/Writerside/writerside.cfg');
        $tree = file_get_contents($root.'/Writerside/tg.tree');
        $workflow = file_get_contents($root.'/.github/workflows/writerside.yml');

        $this->assertIsString($config);
        $this->assertIsString($tree);
        $this->assertIsString($workflow);

        $this->assertStringContainsString('<topics dir="topics"/>', $config);
        $this->assertStringContainsString('<images dir="images" web-path="Laravel-Telegram-Bot"/>', $config);
        $this->assertStringContainsString('<instance src="tg.tree" version="1.14.0"/>', $config);

        foreach ([
            'overview.md',
            'installation.md',
            'configuration.md',
            'usage.md',
            'telegram-setup.md',
            'console-commands.md',
            'webhooks.md',
            'files-and-http.md',
            'payments-passport-games.md',
            'api-surface.md',
            'method-reference.md',
            'troubleshooting.md',
            'maintenance.md',
        ] as $topic) {
            $this->assertFileExists($root.'/Writerside/topics/'.$topic);
            $this->assertStringContainsString('topic="'.$topic.'"', $tree);
        }

        $this->assertStringNotContainsString(
            '<toc-element topic="api-surface.md">'."\n".'        <toc-element topic="method-reference.md"/>',
            $tree,
        );
        $this->assertStringContainsString('<toc-element topic="api-surface.md"/>', $tree);
        $this->assertStringContainsString('<toc-element topic="method-reference.md"/>', $tree);

        $this->assertStringContainsString("INSTANCE: 'Writerside/tg'", $workflow);
        $this->assertStringContainsString("DOCKER_VERSION: '2026.04.8711'", $workflow);
        $this->assertStringContainsString("FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: 'true'", $workflow);
        $this->assertStringContainsString('actions/checkout@v6', $workflow);
        $this->assertStringContainsString('actions/configure-pages@v6', $workflow);
        $this->assertStringContainsString('actions/upload-pages-artifact@v4', $workflow);
        $this->assertStringContainsString('actions/deploy-pages@v5', $workflow);
        $this->assertStringContainsString('JetBrains/writerside-github-action@v4', $workflow);
        $this->assertStringContainsString('Generate docs using Writerside Docker builder', $workflow);
        $this->assertStringNotContainsString('JetBrains/writerside-checker-action@v1', $workflow);
        $this->assertStringNotContainsString('actions/checkout@v4', $workflow);
        $this->assertStringNotContainsString('actions/upload-artifact@v4', $workflow);
        $this->assertStringNotContainsString('actions/download-artifact@v4', $workflow);
        $this->assertStringNotContainsString('actions/upload-artifact@v7', $workflow);
        $this->assertStringNotContainsString('actions/download-artifact@v7', $workflow);
    }

    public function test_writerside_documentation_covers_key_package_workflows(): void
    {
        $root = dirname(__DIR__, 2);
        $overview = file_get_contents($root.'/Writerside/topics/overview.md');
        $usage = file_get_contents($root.'/Writerside/topics/usage.md');
        $webhooks = file_get_contents($root.'/Writerside/topics/webhooks.md');
        $apiSurface = file_get_contents($root.'/Writerside/topics/api-surface.md');
        $maintenance = file_get_contents($root.'/Writerside/topics/maintenance.md');

        $this->assertIsString($overview);
        $this->assertIsString($usage);
        $this->assertIsString($webhooks);
        $this->assertIsString($apiSurface);
        $this->assertIsString($maintenance);

        foreach ([
            'Laravel 12',
            'Laravel 13',
            'Telegram Bot API 10.0',
            'raw `call(method, parameters)` API',
            '![Laravel Telegram Bot package cover](package-cover.png){ width="700" }',
        ] as $requiredOverviewText) {
            $this->assertStringContainsString($requiredOverviewText, $overview);
        }

        $this->assertFileExists($root.'/Writerside/images/package-cover.png');
        $this->assertFileExists($root.'/Writerside/images/package-cover_dark.png');

        foreach ([
            'constructor injection',
            'Facade',
            'configured channel',
            'InputFile::fromPath',
            'TelegramBot::fake()',
            'assertSentMessage',
            'assertNothingSent()',
            'retryAfter()',
            'migrateToChatId()',
        ] as $requiredUsageText) {
            $this->assertStringContainsString($requiredUsageText, $usage);
        }

        foreach ([
            'X-Telegram-Bot-Api-Secret-Token',
            'TELEGRAM_WEBHOOK_REQUIRE_SECRET',
            'TelegramWebhookUpdate',
            'TelegramWebhookCommandHandler',
            'TelegramWebhookCommand',
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
            'TelegramWebhookJob',
            'TELEGRAM_WEBHOOK_QUEUE_ENABLED',
            'TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED',
            '{"ok": true, "duplicate": true}',
        ] as $requiredWebhookText) {
            $this->assertStringContainsString($requiredWebhookText, $webhooks);
        }

        foreach ([
            '| Method | SDK call | Official source |',
            '`sendMessage`',
            '`setWebhook`',
            '`answerGuestQuery`',
            '`sendLivePhoto`',
        ] as $requiredApiText) {
            $this->assertStringContainsString($requiredApiText, $apiSurface);
        }

        foreach ([
            'paid static-analysis token',
            'composer check:telegram-api-surface',
            'Packagist reads versions from Git tags.',
        ] as $requiredMaintenanceText) {
            $this->assertStringContainsString($requiredMaintenanceText, $maintenance);
        }
    }

    public function test_readme_links_to_published_documentation_pages(): void
    {
        $root = dirname(__DIR__, 2);
        $readme = file_get_contents($root.'/README.md');

        $this->assertIsString($readme);

        foreach ([
            '[Overview](https://alexitdev91.github.io/Laravel-Telegram-Bot/overview.html)',
            '[Installation](https://alexitdev91.github.io/Laravel-Telegram-Bot/installation.html)',
            '[Configuration](https://alexitdev91.github.io/Laravel-Telegram-Bot/configuration.html)',
            '[Usage](https://alexitdev91.github.io/Laravel-Telegram-Bot/usage.html)',
            '[End-To-End Setup Guide](https://alexitdev91.github.io/Laravel-Telegram-Bot/telegram-setup.html)',
            '[Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html)',
            '[Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html)',
            '[Files And HTTP](https://alexitdev91.github.io/Laravel-Telegram-Bot/files-and-http.html)',
            '[Payments, Passport, And Games](https://alexitdev91.github.io/Laravel-Telegram-Bot/payments-passport-games.html)',
            '[API Method Support](https://alexitdev91.github.io/Laravel-Telegram-Bot/api-surface.html)',
            '[API Method Reference](https://alexitdev91.github.io/Laravel-Telegram-Bot/method-reference.html)',
            '[Troubleshooting](https://alexitdev91.github.io/Laravel-Telegram-Bot/troubleshooting.html)',
            '[Maintenance](https://alexitdev91.github.io/Laravel-Telegram-Bot/maintenance.html)',
        ] as $publishedLink) {
            $this->assertStringContainsString($publishedLink, $readme);
        }

        foreach ([
            '[docs/API.md](docs/API.md)',
            '[docs/CONSOLE_COMMANDS.md](docs/CONSOLE_COMMANDS.md)',
            '[docs/METHODS.md](docs/METHODS.md)',
            '[docs/SETUP.md](docs/SETUP.md)',
            '[docs/WEBHOOKS.md](docs/WEBHOOKS.md)',
            '[Writerside/topics/overview.md](Writerside/topics/overview.md)',
        ] as $sourceLink) {
            $this->assertStringNotContainsString($sourceLink, $readme);
        }

        $this->assertStringNotContainsString('| Page | What it covers |', $readme);
        $this->assertStringNotContainsString('| --- | --- |', $readme);
    }
}
