<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramSuccessfulPaymentData implements TelegramBotData
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

    public function currency(): ?string
    {
        return $this->stringAt('currency');
    }

    public function totalAmount(): ?int
    {
        return $this->intAt('total_amount');
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
        return $this->arrayAt('order_info');
    }

    public function orderInfoData(): ?TelegramOrderInfoData
    {
        $orderInfo = $this->orderInfo();

        return $orderInfo !== null ? TelegramOrderInfoData::fromPayload($orderInfo) : null;
    }

    public function telegramPaymentChargeId(): ?string
    {
        return $this->stringAt('telegram_payment_charge_id');
    }

    public function providerPaymentChargeId(): ?string
    {
        return $this->stringAt('provider_payment_charge_id');
    }

    public function subscriptionExpirationDate(): ?int
    {
        return $this->intAt('subscription_expiration_date');
    }

    public function isRecurring(): ?bool
    {
        return $this->boolAt('is_recurring');
    }

    public function isFirstRecurring(): ?bool
    {
        return $this->boolAt('is_first_recurring');
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) ? $value : null;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
