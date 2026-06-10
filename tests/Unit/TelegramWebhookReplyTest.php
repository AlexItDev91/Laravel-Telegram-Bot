<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookReply;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TelegramWebhookReplyTest extends TestCase
{
    public function test_serializes_text_reply_for_webhook_response(): void
    {
        $reply = TelegramWebhookReply::text(
            text: 'Ready.',
            chatId: '-1001234567890',
            messageThreadId: '42',
        );

        $this->assertSame([
            'method' => 'sendMessage',
            'text' => 'Ready.',
            'chat_id' => '-1001234567890',
            'message_thread_id' => '42',
        ], $reply->toArray());
    }

    public function test_builds_text_reply_from_effective_update_chat(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 3001,
            'message' => [
                'message_id' => 12,
                'message_thread_id' => 42,
                'text' => '/start',
                'chat' => ['id' => '-1001234567890', 'type' => 'supergroup'],
            ],
        ]);

        $reply = TelegramWebhookReply::fromUpdate($update)->text('Ready.');

        $this->assertSame([
            'method' => 'sendMessage',
            'text' => 'Ready.',
            'chat_id' => '-1001234567890',
            'message_thread_id' => 42,
        ], $reply->toArray());
    }

    public function test_builds_callback_answer_from_update(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 3002,
            'callback_query' => [
                'id' => 'callback-id',
                'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Alex'],
                'data' => 'menu:settings',
            ],
        ]);

        $reply = TelegramWebhookReply::fromUpdate($update)->answerCallback(
            text: 'Saved.',
            showAlert: true,
            cacheTime: 10,
        );

        $this->assertSame([
            'method' => 'answerCallbackQuery',
            'callback_query_id' => 'callback-id',
            'text' => 'Saved.',
            'show_alert' => true,
            'cache_time' => 10,
        ], $reply->toArray());
    }

    public function test_rejects_input_file_uploads_in_webhook_replies(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'telegram-webhook-reply-');
        $this->assertIsString($path);
        file_put_contents($path, 'content');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot contain InputFile uploads');

        try {
            TelegramWebhookReply::send(
                TelegramMessage::document(InputFile::fromPath($path, 'file.txt'))->to('123456789'),
            );
        } finally {
            unlink($path);
        }
    }

    public function test_rejects_reserved_method_payload_key(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter [method] is reserved');

        TelegramWebhookReply::method('sendMessage', [
            'method' => 'deleteMessage',
            'chat_id' => '123456789',
            'text' => 'Ready.',
        ]);
    }
}
