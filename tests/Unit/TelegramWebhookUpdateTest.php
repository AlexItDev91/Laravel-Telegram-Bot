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

    public function test_exposes_payment_passport_and_game_helpers(): void
    {
        $paymentUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 125,
            'message' => [
                'invoice' => ['payload' => 'order-100'],
                'successful_payment' => ['telegram_payment_charge_id' => 'payment-charge'],
                'refunded_payment' => ['telegram_payment_charge_id' => 'refund-charge'],
                'passport_data' => ['credentials' => ['data' => 'encrypted']],
                'game' => ['title' => 'Space Race'],
            ],
        ]);
        $preCheckoutUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 126,
            'pre_checkout_query' => ['id' => 'pre-checkout'],
        ]);
        $shippingUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 127,
            'shipping_query' => ['id' => 'shipping-query'],
        ]);
        $paidMediaUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 128,
            'purchased_paid_media' => ['paid_media_payload' => 'paid-media-order'],
        ]);
        $gameCallbackUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 129,
            'callback_query' => ['game_short_name' => 'space_race'],
        ]);

        $this->assertSame(['telegram_payment_charge_id' => 'payment-charge'], $paymentUpdate->successfulPayment());
        $this->assertSame(['payload' => 'order-100'], $paymentUpdate->invoice());
        $this->assertSame(['telegram_payment_charge_id' => 'refund-charge'], $paymentUpdate->refundedPayment());
        $this->assertSame(['credentials' => ['data' => 'encrypted']], $paymentUpdate->passportData());
        $this->assertSame(['title' => 'Space Race'], $paymentUpdate->game());
        $this->assertSame(['id' => 'shipping-query'], $shippingUpdate->shippingQuery());
        $this->assertSame(['id' => 'pre-checkout'], $preCheckoutUpdate->preCheckoutQuery());
        $this->assertSame(['paid_media_payload' => 'paid-media-order'], $paidMediaUpdate->purchasedPaidMedia());
        $this->assertSame('space_race', $gameCallbackUpdate->gameShortName());
    }
}
