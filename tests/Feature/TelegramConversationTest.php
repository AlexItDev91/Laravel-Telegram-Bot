<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramConversationStore;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationTransition;
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

    public function test_conversation_workflow_supports_guarded_transitions_context_timeout_and_reset(): void
    {
        config()->set('telegram-bot.conversation.enabled', true);

        $manager = app(TelegramConversationManager::class);
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 3002,
            'message' => [
                'message_id' => 2,
                'chat' => ['id' => '123456789', 'type' => 'private'],
                'from' => ['id' => '987654321', 'is_bot' => false, 'first_name' => 'Alex'],
                'text' => 'alex@example.test',
            ],
        ]);
        $workflow = $manager->workflowForUpdate($update, 'support');

        $workflow->start('awaiting_email', [
            'attempts' => 1,
            'email' => 'alex@example.test',
        ]);

        $this->assertTrue($workflow->is('awaiting_email'));
        $this->assertSame('alex@example.test', $workflow->context()->string('email'));
        $this->assertSame(1, $workflow->context()->int('attempts'));

        $blocked = $workflow->transition(TelegramConversationTransition::guarded(
            from: 'awaiting_email',
            to: 'confirmed',
            guard: static fn (mixed $_context, mixed $_update): bool => false,
        ));

        $this->assertNull($blocked);
        $this->assertSame('awaiting_email', $workflow->state());

        $transitioned = $workflow->transition(TelegramConversationTransition::guarded(
            from: 'awaiting_email',
            to: 'confirmed',
            guard: static fn (mixed $_context, mixed $_update): bool => true,
        ), [
            'confirmed' => true,
        ], $update);

        $this->assertSame('confirmed', $transitioned?->state());
        $this->assertTrue($workflow->context()->bool('confirmed'));

        $workflow->timeout(1);
        $this->assertFalse($workflow->isExpired(time()));
        $this->assertTrue($workflow->isExpired(time() + 2));

        $workflow->reset();

        $this->assertNull($workflow->current());
    }
}
