<?php

namespace AlexItDev91\LaravelTelegramBot\Concerns;

use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;

trait SendsTelegramMessageShortcuts
{
    public function text(
        string $text,
        int|string|null $to = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
    ): mixed {
        return $this->sendShortcut(TelegramMessage::text($text), $to, $messageThreadId, $directMessagesTopicId);
    }

    public function photo(
        string|InputFile $photo,
        ?string $caption = null,
        int|string|null $to = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
    ): mixed {
        $message = TelegramMessage::photo($photo);

        if ($caption !== null) {
            $message->caption($caption);
        }

        return $this->sendShortcut($message, $to, $messageThreadId, $directMessagesTopicId);
    }

    public function document(
        string|InputFile $document,
        ?string $caption = null,
        int|string|null $to = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
    ): mixed {
        $message = TelegramMessage::document($document);

        if ($caption !== null) {
            $message->caption($caption);
        }

        return $this->sendShortcut($message, $to, $messageThreadId, $directMessagesTopicId);
    }

    abstract public function send(TelegramMessage $message): mixed;

    private function sendShortcut(
        TelegramMessage $message,
        int|string|null $to,
        int|string|null $messageThreadId,
        int|string|null $directMessagesTopicId,
    ): mixed {
        if ($to !== null) {
            $message->to($to, $messageThreadId, $directMessagesTopicId);
        }

        return $this->send($message);
    }
}
