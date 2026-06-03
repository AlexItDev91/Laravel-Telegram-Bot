<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use AlexItDev91\LaravelTelegramBot\Testing\TelegramBotFake;

class TelegramBotFakeTest extends TestCase
{
    public function test_facade_fake_records_api_calls_and_returns_configured_results(): void
    {
        $fake = TelegramBot::fake()
            ->result(['message_id' => 10], 'sendMessage');

        $result = TelegramBot::sendMessage([
            'chat_id' => '123456789',
            'text' => 'Hello',
        ]);

        $this->assertSame(['message_id' => 10], $result);

        $fake->assertSentMessage(function (array $parameters): bool {
            return $parameters['chat_id'] === '123456789'
                && $parameters['text'] === 'Hello';
        });
        $fake->assertCalled('sendMessage', times: 1);
    }

    public function test_fake_records_named_bot_calls(): void
    {
        $fake = TelegramBot::fake();

        TelegramBot::bot('support')->sendMessage([
            'chat_id' => '123456789',
            'text' => 'Hello support',
        ]);

        $fake->assertCalled('sendMessage', function (array $parameters, string $botName): bool {
            return $botName === 'support'
                && $parameters['text'] === 'Hello support';
        });
    }

    public function test_fake_records_configured_channel_calls_with_chat_defaults(): void
    {
        config()->set('telegram-bot.channels.alerts', [
            'bot' => 'support',
            'chat_id' => '-1001234567890',
            'message_thread_id' => '42',
        ]);

        $fake = TelegramBot::fake()
            ->result(['message_id' => 20], 'sendMessage');

        $result = TelegramBot::channel('alerts')->sendMessage([
            'text' => 'Deploy finished',
        ]);

        $this->assertSame(['message_id' => 20], $result);

        $fake->assertSentMessageToChannel('alerts', function (array $parameters, string $botName): bool {
            return $botName === 'support'
                && $parameters['chat_id'] === '-1001234567890'
                && $parameters['message_thread_id'] === '42'
                && $parameters['text'] === 'Deploy finished';
        });
    }

    public function test_fake_asserts_nothing_was_sent(): void
    {
        $fake = new TelegramBotFake();

        $fake->assertNothingSent();
    }
}
