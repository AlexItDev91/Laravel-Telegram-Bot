<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
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

    public function test_fake_normalizes_backed_enum_payloads_for_calls_and_assertions(): void
    {
        $fake = TelegramBot::fake();

        TelegramBot::sendMessage([
            'chat_id' => '123456789',
            'text' => 'Hello',
            'parse_mode' => TelegramParseMode::HTML,
        ]);

        $fake->assertSent('sendMessage', [
            'text' => 'Hello',
            'parse_mode' => TelegramParseMode::HTML,
        ], times: 1);

        $calls = $fake->calls();

        $this->assertSame('HTML', $calls[0]['parameters']['parse_mode'] ?? null);
    }

    public function test_fake_returns_typed_response_data_for_typed_helpers(): void
    {
        config()->set('telegram-bot.channels.alerts', [
            'bot' => 'support',
            'chat_id' => '-1001234567890',
        ]);

        $fake = TelegramBot::fake()
            ->result([
                'message_id' => 20,
                'date' => 1710000000,
                'chat' => ['id' => '-1001234567890', 'type' => 'supergroup'],
                'text' => 'Deploy finished',
            ], 'sendMessage');

        $message = TelegramBot::channel('alerts')->sendMessageData([
            'text' => 'Deploy finished',
        ]);

        $this->assertSame(20, $message->messageId());
        $this->assertSame('-1001234567890', $message->chat()?->id());

        $fake->assertSentMessageToChannel('alerts', function (array $parameters, string $botName): bool {
            return $botName === 'support'
                && $parameters['chat_id'] === '-1001234567890'
                && $parameters['text'] === 'Deploy finished';
        });
    }

    public function test_fake_asserts_nothing_was_sent(): void
    {
        $fake = new TelegramBotFake();

        $fake->assertNothingSent();
    }

    public function test_testing_dsl_asserts_payload_sequence_token_leakage_webhook_updates_and_conversations(): void
    {
        config()->set('telegram-bot.token', '123456:secret-token');
        config()->set('telegram-bot.conversation.enabled', true);

        $fake = TelegramBot::fake();

        TelegramBot::sendMessage(SendMessageRequestData::make(
            chatId: '123456789',
            text: 'Hello',
        ));
        TelegramBot::sendPhoto([
            'chat_id' => '123456789',
            'photo' => 'file-id',
        ]);

        $fake->assertSent('sendMessage', ['text' => 'Hello'], times: 1);
        $fake->assertSentTypedPayload('sendMessage', SendMessageRequestData::class, times: 1);
        $fake->assertSentSequence(['sendMessage', 'sendPhoto']);
        $fake->assertNoTokenLeakage();

        $update = $fake->fakeWebhookUpdate([
            'update_id' => 5001,
            'message' => [
                'message_id' => 1,
                'chat' => ['id' => '123456789', 'type' => 'private'],
                'text' => 'Hello',
            ],
        ]);

        $manager = app(TelegramConversationManager::class);
        $key = $manager->keyForUpdate($update, 'default');
        $manager->put($key, 'awaiting_name');

        $fake->assertConversationState($manager, $key, 'awaiting_name');
    }
}
