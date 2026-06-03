<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramOrderInfoData implements TelegramBotData
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

    public function name(): ?string
    {
        return $this->stringAt('name');
    }

    public function phoneNumber(): ?string
    {
        return $this->stringAt('phone_number');
    }

    public function email(): ?string
    {
        return $this->stringAt('email');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function shippingAddress(): ?array
    {
        $shippingAddress = $this->payload['shipping_address'] ?? null;

        return is_array($shippingAddress) ? $shippingAddress : null;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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
