<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
final readonly class TelegramPhotoSizeData implements TelegramBotData
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

    public function fileId(): ?string
    {
        return $this->stringAt('file_id');
    }

    public function fileUniqueId(): ?string
    {
        return $this->stringAt('file_unique_id');
    }

    public function width(): ?int
    {
        return $this->intAt('width');
    }

    public function height(): ?int
    {
        return $this->intAt('height');
    }

    public function fileSize(): ?int
    {
        return $this->intAt('file_size');
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
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
