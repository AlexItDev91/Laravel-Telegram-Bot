<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramWebhookUpdate
{
    private const UPDATE_TYPES = [
        'message',
        'edited_message',
        'channel_post',
        'edited_channel_post',
        'business_connection',
        'business_message',
        'edited_business_message',
        'deleted_business_messages',
        'guest_message',
        'message_reaction',
        'message_reaction_count',
        'inline_query',
        'chosen_inline_result',
        'callback_query',
        'shipping_query',
        'pre_checkout_query',
        'purchased_paid_media',
        'poll',
        'poll_answer',
        'my_chat_member',
        'chat_member',
        'chat_join_request',
        'chat_boost',
        'removed_chat_boost',
        'managed_bot',
    ];

    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(
        private array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }

    /**
     * @return list<string>
     */
    public static function updateTypes(): array
    {
        return self::UPDATE_TYPES;
    }

    public function updateId(): ?int
    {
        return isset($this->payload['update_id']) ? (int) $this->payload['update_id'] : null;
    }

    public function type(): ?string
    {
        foreach (self::UPDATE_TYPES as $type) {
            if (array_key_exists($type, $this->payload)) {
                return $type;
            }
        }

        return null;
    }

    public function has(string $type): bool
    {
        return array_key_exists($type, $this->payload);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function data(): ?array
    {
        $type = $this->type();

        return $type !== null && is_array($this->payload[$type] ?? null)
            ? $this->payload[$type]
            : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    public function message(): ?TelegramMessageData
    {
        return $this->messageAt('message');
    }

    public function editedMessage(): ?TelegramMessageData
    {
        return $this->messageAt('edited_message');
    }

    public function channelPost(): ?TelegramMessageData
    {
        return $this->messageAt('channel_post');
    }

    public function editedChannelPost(): ?TelegramMessageData
    {
        return $this->messageAt('edited_channel_post');
    }

    public function businessMessage(): ?TelegramMessageData
    {
        return $this->messageAt('business_message');
    }

    public function editedBusinessMessage(): ?TelegramMessageData
    {
        return $this->messageAt('edited_business_message');
    }

    public function guestMessage(): ?TelegramMessageData
    {
        return $this->messageAt('guest_message');
    }

    public function callbackQuery(): ?TelegramCallbackQueryData
    {
        $callbackQuery = $this->arrayAt('callback_query');

        return $callbackQuery !== null ? TelegramCallbackQueryData::fromPayload($callbackQuery) : null;
    }

    public function inlineQuery(): ?TelegramInlineQueryData
    {
        $inlineQuery = $this->arrayAt('inline_query');

        return $inlineQuery !== null ? TelegramInlineQueryData::fromPayload($inlineQuery) : null;
    }

    public function chosenInlineResult(): ?TelegramChosenInlineResultData
    {
        $chosenInlineResult = $this->arrayAt('chosen_inline_result');

        return $chosenInlineResult !== null ? TelegramChosenInlineResultData::fromPayload($chosenInlineResult) : null;
    }

    public function shippingQueryData(): ?TelegramShippingQueryData
    {
        $shippingQuery = $this->shippingQuery();

        return $shippingQuery !== null ? TelegramShippingQueryData::fromPayload($shippingQuery) : null;
    }

    public function preCheckoutQueryData(): ?TelegramPreCheckoutQueryData
    {
        $preCheckoutQuery = $this->preCheckoutQuery();

        return $preCheckoutQuery !== null ? TelegramPreCheckoutQueryData::fromPayload($preCheckoutQuery) : null;
    }

    public function myChatMember(): ?TelegramChatMemberUpdatedData
    {
        return $this->chatMemberUpdatedAt('my_chat_member');
    }

    public function chatMember(): ?TelegramChatMemberUpdatedData
    {
        return $this->chatMemberUpdatedAt('chat_member');
    }

    public function chatJoinRequest(): ?TelegramChatJoinRequestData
    {
        $chatJoinRequest = $this->arrayAt('chat_join_request');

        return $chatJoinRequest !== null ? TelegramChatJoinRequestData::fromPayload($chatJoinRequest) : null;
    }

    public function effectiveMessage(): ?TelegramMessageData
    {
        foreach ([
            'message',
            'edited_message',
            'channel_post',
            'edited_channel_post',
            'business_message',
            'edited_business_message',
            'guest_message',
            'callback_query.message',
        ] as $key) {
            $message = $this->messageAt($key);

            if ($message !== null) {
                return $message;
            }
        }

        return null;
    }

    public function effectiveChat(): ?TelegramChatData
    {
        $messageChat = $this->effectiveMessage()?->chat();

        if ($messageChat !== null) {
            return $messageChat;
        }

        foreach ([
            'my_chat_member.chat',
            'chat_member.chat',
            'chat_join_request.chat',
            'message_reaction.chat',
            'message_reaction_count.chat',
            'chat_boost.chat',
            'removed_chat_boost.chat',
        ] as $key) {
            $chat = $this->chatAt($key);

            if ($chat !== null) {
                return $chat;
            }
        }

        return null;
    }

    public function effectiveUser(): ?TelegramUserData
    {
        foreach ([
            'message.from',
            'edited_message.from',
            'business_message.from',
            'edited_business_message.from',
            'guest_message.from',
            'callback_query.from',
            'inline_query.from',
            'chosen_inline_result.from',
            'shipping_query.from',
            'pre_checkout_query.from',
            'purchased_paid_media.from',
            'poll_answer.user',
            'my_chat_member.from',
            'chat_member.from',
            'chat_join_request.from',
            'message_reaction.user',
        ] as $key) {
            $user = $this->userAt($key);

            if ($user !== null) {
                return $user;
            }
        }

        return null;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->payload;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function shippingQuery(): ?array
    {
        return $this->arrayAt('shipping_query');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function preCheckoutQuery(): ?array
    {
        return $this->arrayAt('pre_checkout_query');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function purchasedPaidMedia(): ?array
    {
        return $this->arrayAt('purchased_paid_media');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function successfulPayment(): ?array
    {
        return $this->arrayAt('message.successful_payment');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function invoice(): ?array
    {
        return $this->arrayAt('message.invoice');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function refundedPayment(): ?array
    {
        return $this->arrayAt('message.refunded_payment');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function passportData(): ?array
    {
        return $this->arrayAt('message.passport_data');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function game(): ?array
    {
        return $this->arrayAt('message.game');
    }

    public function gameShortName(): ?string
    {
        return $this->callbackQuery()?->gameShortName();
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->get($key);

        return is_array($value) ? $value : null;
    }

    private function messageAt(string $key): ?TelegramMessageData
    {
        $value = $this->arrayAt($key);

        return $value !== null ? TelegramMessageData::fromPayload($value) : null;
    }

    private function chatAt(string $key): ?TelegramChatData
    {
        $value = $this->arrayAt($key);

        return $value !== null ? TelegramChatData::fromPayload($value) : null;
    }

    private function userAt(string $key): ?TelegramUserData
    {
        $value = $this->arrayAt($key);

        return $value !== null ? TelegramUserData::fromPayload($value) : null;
    }

    private function chatMemberUpdatedAt(string $key): ?TelegramChatMemberUpdatedData
    {
        $value = $this->arrayAt($key);

        return $value !== null ? TelegramChatMemberUpdatedData::fromPayload($value) : null;
    }
}
