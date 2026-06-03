<?php

namespace Tests\Feature;

use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use Tests\TestCase;

class TelegramBotExampleTest extends TestCase
{
    public function test_support_bot_sends_typed_message_payload(): void
    {
        $fake = TelegramBot::fake();

        TelegramBot::bot('support')->sendMessage(SendMessageRequestData::make(
            chatId: '123456789',
            text: 'Support ticket created.',
        ));

        $fake->assertSent('sendMessage', ['text' => 'Support ticket created.'], times: 1);
        $fake->assertSentTypedPayload('sendMessage', SendMessageRequestData::class, times: 1);
        $fake->assertNoTokenLeakage();
    }
}
