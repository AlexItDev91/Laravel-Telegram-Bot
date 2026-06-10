<?php

namespace App\Telegram\Handlers;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWizard;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

readonly class ProfileWizardHandler implements TelegramWebhookHandler
{
    public function __construct(
        private TelegramBot $telegram,
        private TelegramConversationManager $conversations,
    ) {
        //
    }

    #[Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $message = $update->effectiveMessage();
        $chatId = $message?->chat()?->id();
        $text = trim((string) $message?->text());

        if ($chatId === null || $text === '') {
            return null;
        }

        $wizard = TelegramConversationWizard::for($this->conversations->workflowForUpdate($update, $botName))
            ->timeout(600)
            ->cancelledMessage('Profile setup cancelled.');

        $wizard->step('awaiting_email', 'email')
            ->prompt('Send your support email address.')
            ->invalid('That does not look like an email address. Try again.')
            ->validate(static fn (mixed $value): bool => is_string($value) && str_contains($value, '@'))
            ->complete('Profile email saved.');

        $result = $wizard->handle($update);

        return $result->hasMessage()
            ? $this->telegram->bot($botName)->sendMessage(SendMessageRequestData::make(
                chatId: $chatId,
                text: $result->message() ?? '',
            ))
            : null;
    }
}
