<?php

namespace AlexItDev91\LaravelTelegramBot\Support;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramApiResponseData;

final readonly class TelegramBotRetryPolicy
{
    /**
     * @param  list<int>  $statusCodes
     */
    public function __construct(
        public bool $enabled = false,
        public int $maxAttempts = 1,
        public bool $retryTransportFailures = true,
        public array $statusCodes = [429, 500, 502, 503, 504],
        public float $baseDelaySeconds = 0.25,
        public bool $sleep = true,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        $statusCodes = $config['status_codes'] ?? [429, 500, 502, 503, 504];

        return new self(
            enabled: ($config['enabled'] ?? false) === true,
            maxAttempts: max(1, (int) ($config['max_attempts'] ?? 1)),
            retryTransportFailures: ($config['retry_transport_failures'] ?? true) === true,
            statusCodes: array_values(array_filter(
                is_array($statusCodes) ? $statusCodes : [],
                static fn (mixed $statusCode): bool => is_int($statusCode),
            )),
            baseDelaySeconds: max(0.0, (float) ($config['base_delay_seconds'] ?? 0.25)),
            sleep: ($config['sleep'] ?? true) === true,
        );
    }

    public function shouldRetryTransportFailure(int $attempt): bool
    {
        return $this->enabled
            && $this->retryTransportFailures
            && $attempt < $this->maxAttempts;
    }

    public function shouldRetryApiResponse(int $attempt, int $statusCode, TelegramApiResponseData $response): bool
    {
        return $this->enabled
            && $attempt < $this->maxAttempts
            && in_array($statusCode, $this->statusCodes, true)
            && (! $response->ok || $statusCode >= 500);
    }

    public function delaySeconds(int $attempt, ?TelegramApiResponseData $response = null): float
    {
        $retryAfter = $response?->parameters['retry_after'] ?? null;

        if (is_int($retryAfter) && $retryAfter >= 0) {
            return (float) $retryAfter;
        }

        return $this->baseDelaySeconds * (2 ** max(0, $attempt - 1));
    }

    public function pause(float $seconds): void
    {
        if (! $this->sleep || $seconds <= 0.0) {
            return;
        }

        usleep((int) round($seconds * 1_000_000));
    }
}
