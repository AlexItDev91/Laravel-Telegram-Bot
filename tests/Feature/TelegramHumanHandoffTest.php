<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationContext;
use AlexItDev91\LaravelTelegramBot\Laravel\Handoff\TelegramHumanHandoff;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;

class TelegramHumanHandoffTest extends TestCase
{
    public function test_handoff_captures_update_context_and_builds_operator_message(): void
    {
        $handoff = TelegramHumanHandoff::fromUpdate(
            update: $this->messageUpdate('I need a person.'),
            reason: 'billing',
            metadata: ['ticket_id' => 'T-123', 'priority' => 'high'],
            openedAt: 1_800_000_000,
        );

        $this->assertSame('billing', $handoff->reason());
        $this->assertSame(1_800_000_000, $handoff->openedAt());
        $this->assertSame('987654321', $handoff->userId());
        $this->assertSame('alex', $handoff->username());
        $this->assertSame('Alex Support', $handoff->displayName());
        $this->assertSame('-1001234567890', $handoff->chatId());
        $this->assertSame(42, $handoff->messageId());
        $this->assertSame(7, $handoff->messageThreadId());
        $this->assertSame(['ticket_id' => 'T-123', 'priority' => 'high'], $handoff->metadata());

        $restored = TelegramHumanHandoff::fromContext(new TelegramConversationContext($handoff->toContext()));

        $this->assertInstanceOf(TelegramHumanHandoff::class, $restored);
        $this->assertSame($handoff->toContext(), $restored->toContext());

        $message = $handoff->toOperatorMessage(
            chatId: '-100999',
            messageThreadId: 99,
            title: 'Support handoff',
        );

        $this->assertSame([
            'chat_id' => '-100999',
            'message_thread_id' => 99,
            'text' => implode("\n", [
                'Support handoff',
                'Reason: billing',
                'Opened at: 2027-01-15T08:00:00+00:00',
                'User ID: 987654321',
                'Username: @alex',
                'Name: Alex Support',
                'Source chat ID: -1001234567890',
                'Source message ID: 42',
                'Source message thread ID: 7',
                'ticket_id: T-123',
                'priority: high',
            ]),
        ], $message->toArray());
    }

    public function test_handoff_opens_restores_and_closes_conversation_state(): void
    {
        config()->set('telegram-bot.conversation.enabled', true);

        $manager = app(TelegramConversationManager::class);
        $update = $this->messageUpdate('/human');
        $workflow = $manager->workflowForUpdate($update, 'support');
        $handoff = TelegramHumanHandoff::fromUpdate($update, 'support-request', openedAt: 1_800_000_001);

        $conversation = $handoff->open($workflow, ['automation_state' => 'support-summary'], ttl: 3600);

        $this->assertSame(TelegramHumanHandoff::STATE, $conversation->state());
        $this->assertSame(TelegramHumanHandoff::STATE, $workflow->state());
        $this->assertSame('support-summary', $workflow->context()->string('automation_state'));
        $this->assertSame('support-request', TelegramHumanHandoff::fromWorkflow($workflow)?->reason());

        TelegramHumanHandoff::close($workflow);

        $this->assertNull($workflow->current());
        $this->assertNull(TelegramHumanHandoff::fromWorkflow($workflow));
    }

    private function messageUpdate(string $text): TelegramWebhookUpdate
    {
        return TelegramWebhookUpdate::fromPayload([
            'update_id' => 6101,
            'message' => [
                'message_id' => 42,
                'message_thread_id' => 7,
                'chat' => ['id' => '-1001234567890', 'type' => 'supergroup', 'title' => 'Customers'],
                'from' => [
                    'id' => '987654321',
                    'is_bot' => false,
                    'first_name' => 'Alex',
                    'last_name' => 'Support',
                    'username' => 'alex',
                ],
                'text' => $text,
            ],
        ]);
    }
}
