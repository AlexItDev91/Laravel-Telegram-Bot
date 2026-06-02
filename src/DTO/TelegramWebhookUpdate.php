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
        $gameShortName = $this->get('callback_query.game_short_name');

        return is_string($gameShortName) ? $gameShortName : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->get($key);

        return is_array($value) ? $value : null;
    }
}
