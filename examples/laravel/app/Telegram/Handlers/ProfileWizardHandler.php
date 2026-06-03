<?php

namespace App\Telegram\Handlers;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationTransition;
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

    #[\Override]
    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $message = $update->effectiveMessage();
        $chatId = $message?->chat()?->id();
        $text = trim((string) $message?->text());

        if ($chatId === null || $text === '') {
            return null;
        }

        $workflow = $this->conversations->workflowForUpdate($update, $botName);

        if ($workflow->current() === null) {
            $workflow->start('awaiting_email', timeoutSeconds: 600);

            return $this->telegram->bot($botName)->sendMessage(SendMessageRequestData::make(
                chatId: $chatId,
                text: 'Send your support email address.',
            ));
        }

        $confirmed = $workflow->transition(TelegramConversationTransition::guarded(
            from: 'awaiting_email',
            to: 'confirmed',
            guard: static fn (mixed $_context, mixed $_update): bool => str_contains($text, '@'),
        ), [
            'email' => $text,
        ], $update);

        if ($confirmed === null) {
            return $this->telegram->bot($botName)->sendMessage(SendMessageRequestData::make(
                chatId: $chatId,
                text: 'That does not look like an email address. Try again.',
            ));
        }

        $workflow->reset();

        return $this->telegram->bot($botName)->sendMessage(SendMessageRequestData::make(
            chatId: $chatId,
            text: 'Profile email saved.',
        ));
    }
}
