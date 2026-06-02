<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramPreCheckoutQueryData implements TelegramBotData
{
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

    public function id(): ?string
    {
        return $this->stringAt('id');
    }

    public function from(): ?TelegramUserData
    {
        $from = $this->payload['from'] ?? null;

        return is_array($from) ? TelegramUserData::fromPayload($from) : null;
    }

    public function currency(): ?string
    {
        return $this->stringAt('currency');
    }

    public function totalAmount(): ?int
    {
        $amount = $this->payload['total_amount'] ?? null;

        return is_int($amount) ? $amount : null;
    }

    public function invoicePayload(): ?string
    {
        return $this->stringAt('invoice_payload');
    }

    public function shippingOptionId(): ?string
    {
        return $this->stringAt('shipping_option_id');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function orderInfo(): ?array
    {
        $orderInfo = $this->payload['order_info'] ?? null;

        return is_array($orderInfo) ? $orderInfo : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
