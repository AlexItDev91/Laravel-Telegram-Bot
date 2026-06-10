<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class DocumentationHardeningTest extends TestCase
{
    public function test_public_examples_prefer_enums_and_constants_for_known_telegram_domains(): void
    {
        $root = dirname(__DIR__, 2);
        $files = [
            $root.'/README.md',
            $root.'/docs/DEEP_LINKS.md',
            $root.'/docs/NOTIFICATIONS.md',
            $root.'/docs/RECIPES.md',
            $root.'/Writerside/topics/deep-links.md',
            $root.'/Writerside/topics/notifications.md',
            $root.'/Writerside/topics/production-recipes.md',
            $root.'/Writerside/topics/usage.md',
            $root.'/resources/boost/skills/telegram-bot-package/SKILL.md',
            $root.'/examples/laravel/app/Notifications/TelegramDeployFinished.php',
            $root.'/examples/laravel/app/Telegram/Commands/StartCommand.php',
        ];

        foreach ($files as $file) {
            $contents = file_get_contents($file);

            $this->assertIsString($contents);

            foreach ([
                "->parseMode('HTML')",
                '->parseMode("HTML")',
                "parseMode: 'HTML'",
                'parseMode: "HTML"',
                "InlineKeyboardButton::callback('",
                "text: 'Deploy finished'",
            ] as $forbiddenSnippet) {
                $this->assertStringNotContainsString($forbiddenSnippet, $contents, $file);
            }
        }
    }

    public function test_documentation_covers_enum_request_builders_nested_input_dtos_and_laravel_config_accessors(): void
    {
        $root = dirname(__DIR__, 2);
        $readme = file_get_contents($root.'/README.md');
        $recipes = file_get_contents($root.'/docs/RECIPES.md');
        $usage = file_get_contents($root.'/Writerside/topics/usage.md');

        $this->assertIsString($readme);
        $this->assertIsString($recipes);
        $this->assertIsString($usage);

        foreach ([
            'LinkPreviewOptions::disabled()',
            'InlineKeyboardMarkup::singleButton',
            'InlineKeyboardButton::callback(self::BUTTON_RETRY',
            'TelegramChatAction',
            'TelegramPollType',
            'TelegramStickerFormat',
            'TelegramUpdateType',
            'TelegramBotLaravelConfig',
        ] as $requiredReadmeText) {
            $this->assertStringContainsString($requiredReadmeText, $readme.$recipes.$usage);
        }
    }
}
