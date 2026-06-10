<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use InvalidArgumentException;

final readonly class TelegramWebhookReplyBuilder
{
    public function __construct(
        private TelegramWebhookUpdate $update,
    ) {
        //
    }

    public function text(string $text): TelegramWebhookReply
    {
        return TelegramWebhookReply::text(
            text: $text,
            chatId: $this->chatId(),
            messageThreadId: $this->messageThreadId(),
        );
    }

    public function photo(string $photo, ?string $caption = null): TelegramWebhookReply
    {
        return TelegramWebhookReply::photo(
            photo: $photo,
            chatId: $this->chatId(),
            caption: $caption,
            messageThreadId: $this->messageThreadId(),
        );
    }

    public function document(string $document, ?string $caption = null): TelegramWebhookReply
    {
        return TelegramWebhookReply::document(
            document: $document,
            chatId: $this->chatId(),
            caption: $caption,
            messageThreadId: $this->messageThreadId(),
        );
    }

    public function answerCallback(
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null,
    ): TelegramWebhookReply {
        return TelegramWebhookReply::answerCallback(
            callbackQueryId: $this->callbackQueryId(),
            text: $text,
            showAlert: $showAlert,
            url: $url,
            cacheTime: $cacheTime,
        );
    }

    private function chatId(): int|string
    {
        $chatId = $this->update->effectiveChat()?->id();

        if ($chatId === null) {
            throw new InvalidArgumentException('Telegram webhook update does not contain an effective chat for a message reply.');
        }

        return $chatId;
    }

    private function messageThreadId(): ?int
    {
        return $this->update->effectiveMessage()?->messageThreadId();
    }

    private function callbackQueryId(): string
    {
        $callbackQueryId = $this->update->callbackQuery()?->id();

        if ($callbackQueryId === null || trim($callbackQueryId) === '') {
            throw new InvalidArgumentException('Telegram webhook update does not contain a callback query id.');
        }

        return $callbackQueryId;
    }
}
