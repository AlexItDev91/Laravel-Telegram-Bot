<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramApiResponseData
{
    /**
     * @param  array<string, mixed>|null  $parameters
     */
    private function __construct(
        public bool $ok,
        public mixed $result,
        public ?string $description,
        public ?int $errorCode,
        public ?array $parameters,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            ok: ($payload['ok'] ?? false) === true,
            result: $payload['result'] ?? true,
            description: isset($payload['description']) ? (string) $payload['description'] : null,
            errorCode: isset($payload['error_code']) ? (int) $payload['error_code'] : null,
            parameters: is_array($payload['parameters'] ?? null) ? $payload['parameters'] : null,
        );
    }
}
