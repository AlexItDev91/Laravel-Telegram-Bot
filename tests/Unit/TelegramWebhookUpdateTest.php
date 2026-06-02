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

    public function test_exposes_typed_inline_query_payloads(): void
    {
        $inlineUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 133,
            'inline_query' => [
                'id' => 'inline-query-id',
                'from' => [
                    'id' => 123,
                    'is_bot' => false,
                    'first_name' => 'Alex',
                ],
                'query' => 'docs',
                'offset' => 'next-page',
                'chat_type' => 'sender',
                'location' => ['latitude' => 50.45, 'longitude' => 30.52],
            ],
        ]);
        $chosenUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 134,
            'chosen_inline_result' => [
                'result_id' => 'result-id',
                'from' => [
                    'id' => '9007199254740991',
                    'is_bot' => false,
                    'first_name' => 'Taylor',
                ],
                'location' => ['latitude' => 48.45, 'longitude' => 35.05],
                'inline_message_id' => 'inline-message-id',
                'query' => 'docs',
            ],
        ]);

        $this->assertSame('inline-query-id', $inlineUpdate->inlineQuery()?->id());
        $this->assertSame(123, $inlineUpdate->inlineQuery()?->from()?->id());
        $this->assertSame('docs', $inlineUpdate->inlineQuery()?->query());
        $this->assertSame('next-page', $inlineUpdate->inlineQuery()?->offset());
        $this->assertSame('sender', $inlineUpdate->inlineQuery()?->chatType());
        $this->assertSame(['latitude' => 50.45, 'longitude' => 30.52], $inlineUpdate->inlineQuery()?->location());
        $this->assertSame('result-id', $chosenUpdate->chosenInlineResult()?->resultId());
        $this->assertSame('9007199254740991', $chosenUpdate->chosenInlineResult()?->from()?->id());
        $this->assertSame(['latitude' => 48.45, 'longitude' => 35.05], $chosenUpdate->chosenInlineResult()?->location());
        $this->assertSame('inline-message-id', $chosenUpdate->chosenInlineResult()?->inlineMessageId());
        $this->assertSame('docs', $chosenUpdate->chosenInlineResult()?->query());
    }

    public function test_exposes_typed_payment_query_payloads_without_replacing_array_helpers(): void
    {
        $shippingUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 135,
            'shipping_query' => [
                'id' => 'shipping-query-id',
                'from' => [
                    'id' => 123,
                    'is_bot' => false,
                    'first_name' => 'Alex',
                ],
                'invoice_payload' => 'order-100',
                'shipping_address' => [
                    'country_code' => 'US',
                    'city' => 'New York',
                ],
            ],
        ]);
        $preCheckoutUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 136,
            'pre_checkout_query' => [
                'id' => 'pre-checkout-id',
                'from' => [
                    'id' => '9007199254740991',
                    'is_bot' => false,
                    'first_name' => 'Taylor',
                ],
                'currency' => 'XTR',
                'total_amount' => 150,
                'invoice_payload' => 'order-101',
                'shipping_option_id' => 'express',
                'order_info' => [
                    'name' => 'Taylor',
                ],
            ],
        ]);

        $this->assertSame(['id' => 'shipping-query-id', 'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Alex'], 'invoice_payload' => 'order-100', 'shipping_address' => ['country_code' => 'US', 'city' => 'New York']], $shippingUpdate->shippingQuery());
        $this->assertSame('shipping-query-id', $shippingUpdate->shippingQueryData()?->id());
        $this->assertSame(123, $shippingUpdate->shippingQueryData()?->from()?->id());
        $this->assertSame('order-100', $shippingUpdate->shippingQueryData()?->invoicePayload());
        $this->assertSame(['country_code' => 'US', 'city' => 'New York'], $shippingUpdate->shippingQueryData()?->shippingAddress());
        $this->assertSame('pre-checkout-id', $preCheckoutUpdate->preCheckoutQueryData()?->id());
        $this->assertSame('9007199254740991', $preCheckoutUpdate->preCheckoutQueryData()?->from()?->id());
        $this->assertSame('XTR', $preCheckoutUpdate->preCheckoutQueryData()?->currency());
        $this->assertSame(150, $preCheckoutUpdate->preCheckoutQueryData()?->totalAmount());
        $this->assertSame('order-101', $preCheckoutUpdate->preCheckoutQueryData()?->invoicePayload());
        $this->assertSame('express', $preCheckoutUpdate->preCheckoutQueryData()?->shippingOptionId());
        $this->assertSame(['name' => 'Taylor'], $preCheckoutUpdate->preCheckoutQueryData()?->orderInfo());
    }

    public function test_exposes_typed_chat_member_and_join_request_payloads(): void
    {
        $memberUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 137,
            'my_chat_member' => [
                'chat' => ['id' => -1001234567890, 'type' => 'supergroup'],
                'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Alex'],
                'date' => 1_780_000_000,
                'old_chat_member' => ['status' => 'member'],
                'new_chat_member' => ['status' => 'administrator'],
                'invite_link' => ['invite_link' => 'https://t.me/+invite'],
                'via_join_request' => true,
                'via_chat_folder_invite_link' => false,
            ],
        ]);
        $joinRequestUpdate = TelegramWebhookUpdate::fromPayload([
            'update_id' => 138,
            'chat_join_request' => [
                'chat' => ['id' => -1001234567890, 'type' => 'supergroup'],
                'from' => ['id' => '9007199254740991', 'is_bot' => false, 'first_name' => 'Taylor'],
                'user_chat_id' => '9007199254740992',
                'date' => 1_780_000_001,
                'bio' => 'Please approve me',
                'invite_link' => ['invite_link' => 'https://t.me/+invite'],
            ],
        ]);

        $this->assertSame(-1001234567890, $memberUpdate->myChatMember()?->chat()?->id());
        $this->assertSame(123, $memberUpdate->myChatMember()?->from()?->id());
        $this->assertSame(1_780_000_000, $memberUpdate->myChatMember()?->date());
        $this->assertSame(['status' => 'member'], $memberUpdate->myChatMember()?->oldChatMember());
        $this->assertSame(['status' => 'administrator'], $memberUpdate->myChatMember()?->newChatMember());
        $this->assertSame(['invite_link' => 'https://t.me/+invite'], $memberUpdate->myChatMember()?->inviteLink());
        $this->assertTrue($memberUpdate->myChatMember()?->viaJoinRequest());
        $this->assertFalse($memberUpdate->myChatMember()?->viaChatFolderInviteLink());
        $this->assertSame(-1001234567890, $joinRequestUpdate->chatJoinRequest()?->chat()?->id());
        $this->assertSame('9007199254740991', $joinRequestUpdate->chatJoinRequest()?->from()?->id());
        $this->assertSame('9007199254740992', $joinRequestUpdate->chatJoinRequest()?->userChatId());
        $this->assertSame(1_780_000_001, $joinRequestUpdate->chatJoinRequest()?->date());
        $this->assertSame('Please approve me', $joinRequestUpdate->chatJoinRequest()?->bio());
        $this->assertSame(['invite_link' => 'https://t.me/+invite'], $joinRequestUpdate->chatJoinRequest()?->inviteLink());
    }

    public function test_exposes_common_typed_message_sub_objects(): void
    {
        $update = TelegramWebhookUpdate::fromPayload([
            'update_id' => 139,
            'message' => [
                'message_id' => 91,
                'text' => 'Receipt',
                'guest_query_id' => 'guest-query-id',
                'reply_to_message' => ['message_id' => 90, 'text' => 'Previous'],
                'guest_bot_caller_user' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Alex'],
                'guest_bot_caller_chat' => ['id' => -1001234567890, 'type' => 'supergroup'],
                'photo' => [
                    ['file_id' => 'small-photo'],
                    ['file_id' => 'large-photo'],
                ],
                'document' => ['file_id' => 'document-id'],
                'entities' => [['type' => 'bot_command', 'offset' => 0, 'length' => 6]],
                'caption_entities' => [['type' => 'bold', 'offset' => 0, 'length' => 4]],
                'successful_payment' => ['telegram_payment_charge_id' => 'payment-charge'],
                'passport_data' => ['credentials' => ['data' => 'encrypted']],
                'game' => ['title' => 'Space Race'],
                'live_photo' => ['width' => 1024, 'height' => 768],
            ],
        ]);

        $message = $update->message();

        $this->assertSame('guest-query-id', $message?->guestQueryId());
        $this->assertSame(90, $message?->replyToMessage()?->messageId());
        $this->assertSame('Previous', $message?->replyToMessage()?->text());
        $this->assertSame(123, $message?->guestBotCallerUser()?->id());
        $this->assertSame(-1001234567890, $message?->guestBotCallerChat()?->id());
        $this->assertSame([['file_id' => 'small-photo'], ['file_id' => 'large-photo']], $message?->photo());
        $this->assertSame(['file_id' => 'document-id'], $message?->document());
        $this->assertSame([['type' => 'bot_command', 'offset' => 0, 'length' => 6]], $message?->entities());
        $this->assertSame([['type' => 'bold', 'offset' => 0, 'length' => 4]], $message?->captionEntities());
        $this->assertSame(['telegram_payment_charge_id' => 'payment-charge'], $message?->successfulPayment());
        $this->assertSame(['credentials' => ['data' => 'encrypted']], $message?->passportData());
        $this->assertSame(['title' => 'Space Race'], $message?->game());
        $this->assertSame(['width' => 1024, 'height' => 768], $message?->livePhoto());
    }
}
