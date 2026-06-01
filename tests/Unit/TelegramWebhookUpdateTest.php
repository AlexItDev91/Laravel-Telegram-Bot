<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use PHPUnit\Framework\TestCase;

class TelegramWebhookUpdateTest extends TestCase
{
    public function test_detects_update_type_and_payload(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 123,
            'guest_message' => [
                'message_id' => 10,
                'text' => 'Guest message',
            ],
        ]);

        $this->assertSame(123, $update->updateId());
        $this->assertSame('guest_message', $update->type());
        $this->assertTrue($update->has('guest_message'));
        $this->assertSame('Guest message', $update->data()['text']);
        $this->assertSame(10, $update->get('guest_message.message_id'));
        $this->assertNull($update->get('message.text'));
    }

    public function test_keeps_unknown_update_payload_available(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 124,
            'future_update_type' => [
                'id' => 'future',
            ],
        ]);

        $this->assertSame(124, $update->updateId());
        $this->assertNull($update->type());
        $this->assertSame('future', $update->get('future_update_type.id'));
        $this->assertSame(['id' => 'future'], $update->payload()['future_update_type']);
    }
}
