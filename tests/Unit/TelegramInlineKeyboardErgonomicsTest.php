<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\Support\TelegramCallbackData;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TelegramInlineKeyboardErgonomicsTest extends TestCase
{
    public function test_builds_fluent_inline_keyboards_with_callback_data_objects(): void
    {
        $callbackData = TelegramCallbackData::action('deploy:retry')
            ->with('id', 42)
            ->with('force', true);

        $keyboard = InlineKeyboardMarkup::make()
            ->callback('Retry', $callbackData)
            ->url('Open run', 'https://example.test/runs/42')
            ->row(
                InlineKeyboardButton::copyText('Copy run id', 'RUN-42'),
                InlineKeyboardButton::switchInlineQueryCurrentChat('Search', 'run RUN-42'),
            );

        $this->assertSame([
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Retry',
                        'callback_data' => 'deploy:retry?id=42&force=1',
                    ],
                ],
                [
                    [
                        'text' => 'Open run',
                        'url' => 'https://example.test/runs/42',
                    ],
                ],
                [
                    [
                        'text' => 'Copy run id',
                        'copy_text' => ['text' => 'RUN-42'],
                    ],
                    [
                        'text' => 'Search',
                        'switch_inline_query_current_chat' => 'run RUN-42',
                    ],
                ],
            ],
        ], $keyboard->toArray());
    }

    public function test_builds_inline_keyboard_columns_from_button_lists(): void
    {
        $keyboard = InlineKeyboardMarkup::fromButtons([
            InlineKeyboardButton::callback('One', 'page:1'),
            InlineKeyboardButton::callback('Two', 'page:2'),
            InlineKeyboardButton::callback('Three', 'page:3'),
        ], columns: 2);

        $this->assertSame([
            'inline_keyboard' => [
                [
                    ['text' => 'One', 'callback_data' => 'page:1'],
                    ['text' => 'Two', 'callback_data' => 'page:2'],
                ],
                [
                    ['text' => 'Three', 'callback_data' => 'page:3'],
                ],
            ],
        ], $keyboard->toArray());
    }

    public function test_callback_data_can_be_parsed_back_into_action_and_parameters(): void
    {
        $callbackData = TelegramCallbackData::parse('deploy:retry?id=42&force=1');

        $this->assertTrue($callbackData->matches('deploy:retry'));
        $this->assertSame('deploy:retry', $callbackData->actionName());
        $this->assertSame('42', $callbackData->parameter('id'));
        $this->assertSame('1', $callbackData->parameter('force'));
        $this->assertSame([
            'id' => '42',
            'force' => '1',
        ], $callbackData->parameters());
        $this->assertSame('deploy:retry?id=42&force=1', $callbackData->toString());
    }

    public function test_inline_keyboard_callback_data_must_fit_telegram_limit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('between 1 and 64 bytes');

        InlineKeyboardButton::callback('Too long', str_repeat('x', 65));
    }

    public function test_callback_data_builder_rejects_payloads_over_telegram_limit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('between 1 and 64 bytes');

        TelegramCallbackData::action('deploy:retry')->with('payload', str_repeat('x', 64));
    }
}
