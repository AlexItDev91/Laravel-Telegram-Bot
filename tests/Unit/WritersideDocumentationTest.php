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
        $this->assertStringContainsString('<instance src="tg.tree" version="1.7.1"/>', $config);

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

        $this->assertStringContainsString("INSTANCE: 'Writerside/tg'", $workflow);
        $this->assertStringContainsString("DOCKER_VERSION: '2026.04.8711'", $workflow);
        $this->assertStringContainsString('JetBrains/writerside-github-action@v4', $workflow);
        $this->assertStringContainsString('JetBrains/writerside-checker-action@v1', $workflow);
        $this->assertStringContainsString('actions/deploy-pages@v4', $workflow);
    }

    public function test_writerside_documentation_covers_key_package_workflows(): void
    {
        $root = dirname(__DIR__, 2);
        $overview = file_get_contents($root.'/Writerside/topics/overview.md');
        $usage = file_get_contents($root.'/Writerside/topics/usage.md');
        $webhooks = file_get_contents($root.'/Writerside/topics/webhooks.md');
        $apiSurface = file_get_contents($root.'/Writerside/topics/api-surface.md');

        $this->assertIsString($overview);
        $this->assertIsString($usage);
        $this->assertIsString($webhooks);
        $this->assertIsString($apiSurface);

        foreach ([
            'Laravel 12',
            'Laravel 13',
            'Telegram Bot API 10.0',
            'raw `call(method, parameters)` API',
        ] as $requiredOverviewText) {
            $this->assertStringContainsString($requiredOverviewText, $overview);
        }

        foreach ([
            'constructor injection',
            'Facade',
            'configured channel',
            'InputFile::fromPath',
        ] as $requiredUsageText) {
            $this->assertStringContainsString($requiredUsageText, $usage);
        }

        foreach ([
            'X-Telegram-Bot-Api-Secret-Token',
            'TELEGRAM_WEBHOOK_REQUIRE_SECRET',
            'TelegramWebhookUpdate',
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
    }
}
