<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotRequestTelemetryData implements TelegramBotData
{
    public function __construct(
        public string $method,
        public bool $ok,
        public float $durationMs,
        public int $attempts,
        public ?int $statusCode = null,
        public ?int $telegramErrorCode = null,
        public ?string $exception = null,
        public bool $hasFiles = false,
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'ok' => $this->ok,
            'duration_ms' => $this->durationMs,
            'attempts' => $this->attempts,
            'status_code' => $this->statusCode,
            'telegram_error_code' => $this->telegramErrorCode,
            'exception' => $this->exception,
            'has_files' => $this->hasFiles,
        ];
    }
}
