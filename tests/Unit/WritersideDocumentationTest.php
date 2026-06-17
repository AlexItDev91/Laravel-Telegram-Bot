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
        $this->assertStringContainsString('<instance src="tg.tree" version="2.12.5"/>', $config);
        $this->assertFileExists($root.'/Writerside/cfg/buildprofiles.xml');
        $this->assertFileExists($root.'/Writerside/cfg/static/custom.css');
        $this->assertFileExists($root.'/Writerside/cfg/static/local-search.js');
        $this->assertFileExists($root.'/scripts/build-writerside-static-search.php');

        $buildProfiles = file_get_contents($root.'/Writerside/cfg/buildprofiles.xml');
        $customCss = file_get_contents($root.'/Writerside/cfg/static/custom.css');

        $this->assertIsString($buildProfiles);
        $this->assertIsString($customCss);
        $this->assertStringContainsString('<code-soft-wrap>true</code-soft-wrap>', $buildProfiles);
        $this->assertStringContainsString('<custom-css>custom.css</custom-css>', $buildProfiles);
        $this->assertStringContainsString('table-layout: fixed', $customCss);
        $this->assertStringContainsString('overflow-wrap: anywhere', $customCss);
        $this->assertStringContainsString('.ltb-doc-search', $customCss);

        foreach ([
            'overview.md',
            'installation.md',
            'configuration.md',
            'usage.md',
            'mini-apps.md',
            'deep-links.md',
            'telegram-setup.md',
            'console-commands.md',
            'webhooks.md',
            'notifications.md',
            'typed-responses.md',
            'production-recipes.md',
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
        $this->assertStringContainsString('Build static documentation search', $workflow);
        $this->assertStringContainsString('php scripts/build-writerside-static-search.php site', $workflow);
        $this->assertStringNotContainsString('JetBrains/writerside-checker-action@v1', $workflow);
        $this->assertStringNotContainsString('actions/checkout@v4', $workflow);
        $this->assertStringNotContainsString('actions/upload-artifact@v4', $workflow);
        $this->assertStringNotContainsString('actions/download-artifact@v4', $workflow);
        $this->assertStringNotContainsString('actions/upload-artifact@v7', $workflow);
        $this->assertStringNotContainsString('actions/download-artifact@v7', $workflow);
        $this->assertStringNotContainsString('ALGOLIA_', $workflow);
        $this->assertStringNotContainsString('algolia-publisher', $workflow);
    }

    public function test_writerside_documentation_covers_key_package_workflows(): void
    {
        $root = dirname(__DIR__, 2);
        $overview = file_get_contents($root.'/Writerside/topics/overview.md');
        $usage = file_get_contents($root.'/Writerside/topics/usage.md');
        $miniApps = file_get_contents($root.'/Writerside/topics/mini-apps.md');
        $deepLinks = file_get_contents($root.'/Writerside/topics/deep-links.md');
        $webhooks = file_get_contents($root.'/Writerside/topics/webhooks.md');
        $notifications = file_get_contents($root.'/Writerside/topics/notifications.md');
        $typedResponses = file_get_contents($root.'/Writerside/topics/typed-responses.md');
        $recipes = file_get_contents($root.'/Writerside/topics/production-recipes.md');
        $apiSurface = file_get_contents($root.'/Writerside/topics/api-surface.md');
        $troubleshooting = file_get_contents($root.'/Writerside/topics/troubleshooting.md');
        $maintenance = file_get_contents($root.'/Writerside/topics/maintenance.md');

        $this->assertIsString($overview);
        $this->assertIsString($usage);
        $this->assertIsString($miniApps);
        $this->assertIsString($deepLinks);
        $this->assertIsString($webhooks);
        $this->assertIsString($notifications);
        $this->assertIsString($typedResponses);
        $this->assertIsString($recipes);
        $this->assertIsString($apiSurface);
        $this->assertIsString($troubleshooting);
        $this->assertIsString($maintenance);

        foreach ([
            'Laravel 13',
            'Version `v1.19.1` is the final 1.x release',
            'Telegram Bot API 10.1',
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
            'Dynamic Bots And Destinations',
            'TelegramBot::to($chatId, token: $token)',
            'botToken($token)',
            "TelegramBot::channel('alerts')->text",
            '->photo(',
            'TelegramBot::document',
            'TelegramCallbackData::action',
            'InlineKeyboardMarkup::make()',
            'TelegramMessage::text',
            'TelegramMessage::photo',
            'TelegramMessage::document',
            'InputFile::fromPath',
            'LinkPreviewOptions::disabled()',
            'InlineKeyboardMarkup::singleButton',
            'TelegramBot::fake()',
            'assertSentMessage',
            'assertNothingSent()',
            'retryAfter()',
            'migrateToChatId()',
        ] as $requiredUsageText) {
            $this->assertStringContainsString($requiredUsageText, $usage);
        }

        foreach ([
            'Mini Apps',
            'TelegramMiniAppInitDataValidator',
            'Telegram.WebApp.initData',
            'maxAgeSeconds',
            'TelegramMiniAppUserData',
            'TelegramMiniAppChatData',
            'core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app',
        ] as $requiredMiniAppText) {
            $this->assertStringContainsString($requiredMiniAppText, $miniApps);
        }

        foreach ([
            'Deep Links',
            'TelegramDeepLink',
            'TelegramStartPayloadSigner',
            'startgroup',
            'startapp',
            'startattach',
            '64-character start parameter limit',
            'core.telegram.org/bots/features#deep-linking',
        ] as $requiredDeepLinkText) {
            $this->assertStringContainsString($requiredDeepLinkText, $deepLinks);
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
            'TelegramWebhookReply',
            'answerCallback',
            'queued handlers',
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
            'TelegramWebhookMiddleware',
            'telegram-bot.webhook.middleware',
            'TelegramWebhookJob',
            'TELEGRAM_WEBHOOK_QUEUE_ENABLED',
            'TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED',
            'TelegramConversationManager',
            'TelegramHumanHandoff',
            'TELEGRAM_CONVERSATION_ENABLED',
            '{"ok": true, "duplicate": true}',
            'TelegramWebhookHandled',
            'TelegramWebhookFailed',
            'TelegramWebhookQueued',
            'TelegramWebhookDuplicateSkipped',
        ] as $requiredWebhookText) {
            $this->assertStringContainsString($requiredWebhookText, $webhooks);
        }

        foreach ([
            'TelegramBotNotificationChannel',
            'TelegramNotificationMessage',
            'routeNotificationForTelegram',
            "Notification::route('telegram'",
            'TelegramBotRequestData',
            '`token` or `bot_token`',
            'botToken()',
            'TelegramBot::fake()',
            'TelegramParseMode::HTML',
        ] as $requiredNotificationText) {
            $this->assertStringContainsString($requiredNotificationText, $notifications);
        }

        foreach ([
            'Typed Responses',
            'callData()',
            'getMeData()',
            'sendMessageData()',
            'getUpdatesData()',
            'TelegramBotResultData',
            'TelegramFileData',
            'TelegramWebhookInfoData',
            'TelegramBot::fake()',
        ] as $requiredTypedResponsesText) {
            $this->assertStringContainsString($requiredTypedResponsesText, $typedResponses);
        }

        foreach ([
            'Production Recipes',
            'SendMessageData',
            'EditMessageTextData',
            'SendPhotoData',
            'SendDocumentData',
            'AnswerCallbackQueryData',
            'retryAfter()',
            'migrateToChatId()',
            'TelegramBotRateLimitException',
            'ShouldBeUnique',
            'assertNoTokenLeakage()',
            'TelegramWebhookHandled',
            'TelegramBotNotificationChannel',
            'Typed Response Accessors',
            'Fluent Outbound Messages',
            'Outbound\TelegramMessage',
            'TelegramMessage::text',
            'Method-Scoped Request DTOs',
            'TelegramBotRequestData::forMethod()',
            'TelegramChatAction',
            'TelegramPollType',
            'TelegramUpdateType',
            'Webhook Middleware',
            'Conversations',
            'TelegramConversationWizard',
            'Human Handoff',
            'TelegramHumanHandoff',
            'Scenario Recipes',
            'Operations Alerts',
            'Ecommerce Order Updates',
            '->text(sprintf(',
            'Support Intake',
            'Admin-Channel Notifications',
            'TelegramCallbackData::action',
            'assertSentMessageToChannel',
            'TelegramBot::to($order->telegram_chat_id, token: $order->store->telegram_bot_token)',
            'examples/laravel',
            'TelegramParseMode::HTML',
        ] as $requiredRecipesText) {
            $this->assertStringContainsString($requiredRecipesText, $recipes);
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

        $this->assertStringContainsString('## Debug Checklist', $troubleshooting);
        $this->assertStringContainsString('1. The host Laravel app has the expected env values loaded.', $troubleshooting);
        $this->assertStringContainsString('8. Slow webhook work is dispatched to jobs instead of blocking Telegram\'s request.', $troubleshooting);
        $this->assertStringNotContainsString('| Step | What to verify |', $troubleshooting);

        foreach ([
            'paid static-analysis token',
            'qodana.yaml',
            '.github/workflows/qodana.yml',
            'Published documentation search is fully static.',
            'scripts/build-writerside-static-search.php',
            'local-search-index.json',
            'external search service',
            'composer check:telegram-api-surface',
            'composer generate:telegram-api-schema',
            'TelegramBotApiMethodSchema',
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
            '[Mini Apps](https://alexitdev91.github.io/Laravel-Telegram-Bot/mini-apps.html)',
            '[Deep Links](https://alexitdev91.github.io/Laravel-Telegram-Bot/deep-links.html)',
            '[End-To-End Setup Guide](https://alexitdev91.github.io/Laravel-Telegram-Bot/telegram-setup.html)',
            '[Console Commands](https://alexitdev91.github.io/Laravel-Telegram-Bot/console-commands.html)',
            '[Webhooks](https://alexitdev91.github.io/Laravel-Telegram-Bot/webhooks.html)',
            '[Notifications](https://alexitdev91.github.io/Laravel-Telegram-Bot/notifications.html)',
            '[Typed Responses](https://alexitdev91.github.io/Laravel-Telegram-Bot/typed-responses.html)',
            '[Production Recipes](https://alexitdev91.github.io/Laravel-Telegram-Bot/production-recipes.html)',
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
        $this->assertStringNotContainsString('| API Method Reference |', $readme);
    }
}
