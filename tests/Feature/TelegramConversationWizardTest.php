<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWorkflow;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWizard;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWizardResult;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;

class TelegramConversationWizardTest extends TestCase
{
    public function test_wizard_handles_text_steps_validation_resume_and_completion(): void
    {
        config()->set('telegram-bot.conversation.enabled', true);

        $manager = app(TelegramConversationManager::class);
        $workflow = $manager->workflowForUpdate($this->messageUpdate('/profile', 5101), 'support');
        $wizard = $this->profileWizard($workflow);

        $started = $wizard->handle($this->messageUpdate('/profile', 5101));

        $this->assertSame(TelegramConversationWizardResult::STATUS_STARTED, $started->status());
        $this->assertSame('email', $started->step());
        $this->assertSame('Send your support email address.', $started->message());
        $this->assertSame('email', $workflow->state());

        $invalid = $wizard->handle($this->messageUpdate('not-an-email', 5102));

        $this->assertSame(TelegramConversationWizardResult::STATUS_INVALID, $invalid->status());
        $this->assertTrue($invalid->invalid());
        $this->assertSame('email', $workflow->state());
        $this->assertNull($workflow->context()->string('email'));

        $advanced = $wizard->handle($this->messageUpdate('alex@example.test', 5103));

        $this->assertSame(TelegramConversationWizardResult::STATUS_ADVANCED, $advanced->status());
        $this->assertSame('summary', $advanced->step());
        $this->assertSame('email', $advanced->previousStep());
        $this->assertSame('Describe the support request.', $advanced->message());
        $this->assertSame('alex@example.test', $advanced->context()->string('email'));
        $this->assertSame('summary', $workflow->state());

        $completed = $wizard->handle($this->messageUpdate('Need help with billing.', 5104));

        $this->assertTrue($completed->completed());
        $this->assertSame(TelegramConversationWizardResult::STATUS_COMPLETED, $completed->status());
        $this->assertSame('Support request saved.', $completed->message());
        $this->assertSame('alex@example.test', $completed->context()->string('email'));
        $this->assertSame('Need help with billing.', $completed->context()->string('summary'));
        $this->assertNull($workflow->current());
    }

    public function test_wizard_supports_back_and_cancel_commands(): void
    {
        config()->set('telegram-bot.conversation.enabled', true);

        $manager = app(TelegramConversationManager::class);
        $workflow = $manager->workflowForUpdate($this->messageUpdate('/support', 5201), 'support');
        $wizard = $this->profileWizard($workflow);

        $wizard->handle($this->messageUpdate('/support', 5201));
        $wizard->handle($this->messageUpdate('alex@example.test', 5202));

        $back = $wizard->handle($this->messageUpdate('/back', 5203));

        $this->assertSame(TelegramConversationWizardResult::STATUS_BACK, $back->status());
        $this->assertSame('email', $back->step());
        $this->assertSame('summary', $back->previousStep());
        $this->assertSame('Send your support email address.', $back->message());
        $this->assertSame('email', $workflow->state());
        $this->assertSame('alex@example.test', $workflow->context()->string('email'));

        $cancelled = $wizard->handle($this->messageUpdate('/cancel', 5204));

        $this->assertTrue($cancelled->cancelled());
        $this->assertSame(TelegramConversationWizardResult::STATUS_CANCELLED, $cancelled->status());
        $this->assertSame('Wizard cancelled.', $cancelled->message());
        $this->assertNull($workflow->current());
    }

    public function test_wizard_uses_callback_query_data_for_step_transitions(): void
    {
        config()->set('telegram-bot.conversation.enabled', true);

        $manager = app(TelegramConversationManager::class);
        $workflow = $manager->workflowForUpdate($this->messageUpdate('/plan', 5301), 'sales');
        $wizard = TelegramConversationWizard::for($workflow);
        $wizard->step('plan', 'plan')
            ->prompt('Choose a plan.')
            ->invalid('Choose one of the listed plans.')
            ->validate(static fn (mixed $value): bool => in_array($value, ['basic', 'pro'], true))
            ->complete('Plan selected.');

        $wizard->handle($this->messageUpdate('/plan', 5301));

        $completed = $wizard->handle($this->callbackUpdate('pro', 5302));

        $this->assertSame(TelegramConversationWizardResult::STATUS_COMPLETED, $completed->status());
        $this->assertSame('pro', $completed->context()->string('plan'));
        $this->assertSame('Plan selected.', $completed->message());
        $this->assertNull($workflow->current());
    }

    private function profileWizard(TelegramConversationWorkflow $workflow): TelegramConversationWizard
    {
        $wizard = TelegramConversationWizard::for($workflow)
            ->cancelledMessage('Wizard cancelled.');

        $wizard->step('email', 'email')
            ->prompt('Send your support email address.')
            ->invalid('That does not look like an email address. Try again.')
            ->validate(static fn (mixed $value): bool => is_string($value) && str_contains($value, '@'))
            ->next('summary');

        $wizard->step('summary', 'summary')
            ->prompt('Describe the support request.')
            ->validate(static fn (mixed $value): bool => is_string($value) && $value !== '')
            ->complete('Support request saved.');

        return $wizard;
    }

    private function messageUpdate(string $text, int $updateId): TelegramWebhookUpdate
    {
        return TelegramWebhookUpdate::fromPayload([
            'update_id' => $updateId,
            'message' => [
                'message_id' => $updateId,
                'chat' => ['id' => '123456789', 'type' => 'private'],
                'from' => ['id' => '987654321', 'is_bot' => false, 'first_name' => 'Alex'],
                'text' => $text,
            ],
        ]);
    }

    private function callbackUpdate(string $data, int $updateId): TelegramWebhookUpdate
    {
        return TelegramWebhookUpdate::fromPayload([
            'update_id' => $updateId,
            'callback_query' => [
                'id' => 'callback-'.$updateId,
                'from' => ['id' => '987654321', 'is_bot' => false, 'first_name' => 'Alex'],
                'message' => [
                    'message_id' => $updateId,
                    'chat' => ['id' => '123456789', 'type' => 'private'],
                    'text' => 'Choose a plan.',
                ],
                'data' => $data,
            ],
        ]);
    }
}
