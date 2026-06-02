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
        $this->assertStringContainsString('<instance src="tg.tree" version="1.8.1"/>', $config);

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
        ] as $requiredUsageText) {
            $this->assertStringContainsString($requiredUsageText, $usage);
        }

        foreach ([
            'X-Telegram-Bot-Api-Secret-Token',
            'TELEGRAM_WEBHOOK_REQUIRE_SECRET',
            'TelegramWebhookUpdate',
            'effectiveMessage()',
            'effectiveChat()',
            'effectiveUser()',
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
