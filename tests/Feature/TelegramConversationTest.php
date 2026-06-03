<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramConversationStore;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;

class TelegramConversationTest extends TestCase
{
    public function test_conversation_manager_persists_state_for_effective_update_subject(): void
    {
        config()->set('telegram-bot.conversation.enabled', true);
        config()->set('telegram-bot.conversation.ttl', 600);

        $manager = app(TelegramConversationManager::class);
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 3001,
            'message' => [
                'message_id' => 1,
                'chat' => ['id' => '-1001234567890', 'type' => 'supergroup'],
                'from' => ['id' => '987654321', 'is_bot' => false, 'first_name' => 'Alex'],
                'text' => 'step',
            ],
        ]);

        $key = $manager->keyForUpdate($update, 'support');
        $conversation = $manager->putForUpdate($update, 'support', 'awaiting_email', [
            'attempts' => 1,
        ]);

        $this->assertSame($key, $conversation->key());
        $this->assertSame('awaiting_email', $conversation->state());
        $this->assertSame(1, $conversation->get('attempts'));
        $this->assertSame('awaiting_email', $manager->forUpdate($update, 'support')?->state());

        $manager->forgetForUpdate($update, 'support');

        $this->assertNull($manager->forUpdate($update, 'support'));
    }

    public function test_conversation_store_contract_is_bound(): void
    {
        $this->assertInstanceOf(TelegramConversationStore::class, app(TelegramConversationStore::class));
    }
}
