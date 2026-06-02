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

    public function test_exposes_typed_message_chat_and_user_accessors(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 130,
            'message' => [
                'message_id' => 77,
                'message_thread_id' => 12,
                'date' => 1_780_000_000,
                'text' => '/start',
                'is_topic_message' => true,
                'from' => [
                    'id' => '9007199254740991',
                    'is_bot' => false,
                    'first_name' => 'Alex',
                    'username' => 'alex',
                    'language_code' => 'en',
                    'supports_guest_queries' => true,
                ],
                'chat' => [
                    'id' => -1001234567890,
                    'type' => 'supergroup',
                    'title' => 'Support',
                    'is_forum' => true,
                ],
            ],
        ]);

        $message = $update->message();
        $chat = $update->effectiveChat();
        $user = $update->effectiveUser();

        $this->assertSame($message?->toArray(), $update->effectiveMessage()?->toArray());
        $this->assertSame(77, $message?->messageId());
        $this->assertSame(12, $message?->messageThreadId());
        $this->assertSame(1_780_000_000, $message?->date());
        $this->assertSame('/start', $message?->text());
        $this->assertTrue($message?->isTopicMessage());
        $this->assertSame(-1001234567890, $chat?->id());
        $this->assertSame('supergroup', $chat?->type());
        $this->assertSame('Support', $chat?->title());
        $this->assertTrue($chat?->isForum());
        $this->assertSame('9007199254740991', $user?->id());
        $this->assertFalse($user?->isBot());
        $this->assertSame('Alex', $user?->firstName());
        $this->assertSame('alex', $user?->username());
        $this->assertSame('en', $user?->languageCode());
        $this->assertTrue($user?->supportsGuestQueries());
    }

    public function test_effective_accessors_fall_back_to_callback_query_context(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 131,
            'callback_query' => [
                'id' => 'callback-id',
                'from' => [
                    'id' => 42,
                    'is_bot' => false,
                    'first_name' => 'Taylor',
                ],
                'message' => [
                    'message_id' => 88,
                    'caption' => 'Choose an option',
                    'from' => [
                        'id' => 777000,
                        'is_bot' => true,
                        'first_name' => 'Button Bot',
                    ],
                    'chat' => [
                        'id' => '9007199254740992',
                        'type' => 'private',
                        'first_name' => 'Taylor',
                    ],
                ],
            ],
        ]);

        $this->assertSame(88, $update->effectiveMessage()?->messageId());
        $this->assertSame('Choose an option', $update->effectiveMessage()?->caption());
        $this->assertSame('9007199254740992', $update->effectiveChat()?->id());
        $this->assertSame('private', $update->effectiveChat()?->type());
        $this->assertSame('callback-id', $update->callbackQuery()?->id());
        $this->assertSame(42, $update->callbackQuery()?->from()?->id());
        $this->assertSame(88, $update->callbackQuery()?->message()?->messageId());
        $this->assertSame(42, $update->effectiveUser()?->id());
        $this->assertSame('Taylor', $update->effectiveUser()?->firstName());
    }

    public function test_exposes_typed_callback_query_payload(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 132,
            'callback_query' => [
                'id' => 'callback-id',
                'from' => [
                    'id' => '9007199254740991',
                    'is_bot' => false,
                    'first_name' => 'Alex',
                    'username' => 'alex',
                ],
                'message' => [
                    'message_id' => 90,
                    'text' => 'Pick one',
                    'chat' => [
                        'id' => -1001234567890,
                        'type' => 'supergroup',
                    ],
                ],
                'inline_message_id' => 'inline-message-id',
                'chat_instance' => 'chat-instance',
                'data' => 'menu:settings',
                'game_short_name' => 'space_race',
            ],
        ]);

        $callbackQuery = $update->callbackQuery();

        $this->assertSame('callback-id', $callbackQuery?->id());
        $this->assertSame('9007199254740991', $callbackQuery?->from()?->id());
        $this->assertSame('alex', $callbackQuery?->from()?->username());
        $this->assertSame(90, $callbackQuery?->message()?->messageId());
        $this->assertSame('Pick one', $callbackQuery?->message()?->text());
        $this->assertSame(-1001234567890, $callbackQuery?->message()?->chat()?->id());
        $this->assertSame('inline-message-id', $callbackQuery?->inlineMessageId());
        $this->assertSame('chat-instance', $callbackQuery?->chatInstance());
        $this->assertSame('menu:settings', $callbackQuery?->data());
        $this->assertSame('space_race', $callbackQuery?->gameShortName());
        $this->assertSame('space_race', $update->gameShortName());
        $this->assertSame($update->get('callback_query'), $callbackQuery?->toArray());
    }
}
