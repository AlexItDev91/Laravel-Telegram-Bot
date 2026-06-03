<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramDocumentData implements TelegramBotData
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

    public function thumbnail(): ?TelegramPhotoSizeData
    {
        $thumbnail = $this->payload['thumbnail'] ?? null;

        return is_array($thumbnail) ? TelegramPhotoSizeData::fromPayload($thumbnail) : null;
    }

    public function fileName(): ?string
    {
        return $this->stringAt('file_name');
    }

    public function mimeType(): ?string
    {
        return $this->stringAt('mime_type');
    }

    public function fileSize(): ?int
    {
        return $this->intAt('file_size');
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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
