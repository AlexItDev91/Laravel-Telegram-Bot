<?php

namespace AlexItDev91\LaravelTelegramBot;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotObserver;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotRateLimiter;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramApiResponseData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestTelemetryData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotConfigurationException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotTransportException;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotResultFactory;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotRetryPolicy;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use JsonException;
use Psr\Log\LoggerInterface;
use Throwable;

class TelegramBotClient implements TelegramBotClientContract
{
    use TelegramBotApiMethods;
    use TelegramBotTypedApiMethods;

    public function __construct(
        private readonly TelegramBotConfigData $config,
        private ?ClientInterface $httpClient = null,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?TelegramBotRetryPolicy $retryPolicy = null,
        private readonly ?TelegramBotRateLimiter $rateLimiter = null,
        private readonly ?TelegramBotObserver $observer = null,
    ) {
        //
    }

    public static function make(
        ?string $token,
        string $apiUrl = 'https://api.telegram.org',
        float $timeout = 10.0,
        ?ClientInterface $httpClient = null,
        ?LoggerInterface $logger = null,
        ?TelegramBotRetryPolicy $retryPolicy = null,
        ?TelegramBotRateLimiter $rateLimiter = null,
        ?TelegramBotObserver $observer = null,
    ): self
    {
        return new self(
            config: new TelegramBotConfigData($token, $apiUrl, $timeout),
            httpClient: $httpClient,
            logger: $logger,
            retryPolicy: $retryPolicy,
            rateLimiter: $rateLimiter,
            observer: $observer,
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    #[Override]
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $this->assertConfigured();
        $method = $this->normalizeMethod($method);
        $this->assertMethodName($method);

        $request = $parameters instanceof TelegramBotRequestData
            ? $parameters
            : TelegramBotRequestData::fromArray($parameters);
        $this->assertRequestMatchesMethod($request, $method);

        $execute = fn (): mixed => $this->sendWithRetries($method, $request);

        if ($this->rateLimiter !== null) {
            return $this->rateLimiter->throttle($method, $execute);
        }

        return $execute();
    }

    private function sendWithRetries(string $method, TelegramBotRequestData $request): mixed
    {
        $startedAt = microtime(true);
        $attempt = 0;

        while (true) {
            $attempt++;

            try {
                [$apiResponse, $statusCode] = $this->requestApi($method, $request);
            } catch (TelegramBotTransportException $exception) {
                if ($this->retryPolicy()->shouldRetryTransportFailure($attempt)) {
                    $this->retryPolicy()->pause($this->retryPolicy()->delaySeconds($attempt));

                    continue;
                }

                $this->recordTelemetry($method, $request, false, $startedAt, $attempt, exception: $exception);

                throw $exception;
            }

            if (! $apiResponse->ok) {
                if ($this->retryPolicy()->shouldRetryApiResponse($attempt, $statusCode, $apiResponse)) {
                    $this->retryPolicy()->pause($this->retryPolicy()->delaySeconds($attempt, $apiResponse));

                    continue;
                }

                $this->logger?->warning('Telegram Bot API request failed.', [
                    'method' => $method,
                    'telegram_error_code' => $apiResponse->errorCode,
                ]);

                $exception = new TelegramBotApiException(
                    $apiResponse->description ?? 'Telegram Bot API request failed.',
                    $apiResponse->errorCode,
                    $apiResponse->parameters ?? [],
                );

                $this->recordTelemetry($method, $request, false, $startedAt, $attempt, $statusCode, $apiResponse->errorCode, $exception);

                throw $exception;
            }

            $this->recordTelemetry($method, $request, true, $startedAt, $attempt, $statusCode);

            return $apiResponse->result;
        }
    }

    /**
     * @return array{TelegramApiResponseData, int}
     */
    private function requestApi(string $method, TelegramBotRequestData $request): array
    {
        try {
            $response = $this->httpClient()->request(
                'POST',
                sprintf('%s/bot%s/%s', rtrim($this->config->apiUrl, '/'), $this->config->token, $method),
                $this->buildRequestOptions($request),
            );
        } catch (GuzzleException $exception) {
            $this->logger?->error('Telegram Bot API transport request failed.', [
                'method' => $method,
                'exception' => $exception::class,
            ]);

            throw new TelegramBotTransportException($this->sanitizeTransportMessage($exception->getMessage()), previous: $exception);
        }

        try {
            $payload = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $payload = null;
        }

        if (! is_array($payload)) {
            $this->logger?->error('Telegram Bot API returned a non-JSON response.', [
                'method' => $method,
                'status_code' => $response->getStatusCode(),
            ]);

            throw new TelegramBotTransportException('Telegram Bot API returned a non-JSON response.');
        }

        $this->assertValidResponsePayload($payload, $method);

        return [TelegramApiResponseData::fromPayload($payload), $response->getStatusCode()];
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function callData(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        return TelegramBotResultFactory::from($method, $this->call($method, $parameters));
    }

    private function retryPolicy(): TelegramBotRetryPolicy
    {
        return $this->retryPolicy ?? new TelegramBotRetryPolicy();
    }

    private function recordTelemetry(
        string $method,
        TelegramBotRequestData $request,
        bool $ok,
        float $startedAt,
        int $attempts,
        ?int $statusCode = null,
        ?int $telegramErrorCode = null,
        ?Throwable $exception = null,
    ): void {
        if ($this->observer === null) {
            return;
        }

        try {
            $this->observer->record(new TelegramBotRequestTelemetryData(
                method: $method,
                ok: $ok,
                durationMs: round((microtime(true) - $startedAt) * 1000, 3),
                attempts: $attempts,
                statusCode: $statusCode,
                telegramErrorCode: $telegramErrorCode,
                exception: $exception !== null ? $exception::class : null,
                hasFiles: $request->containsFiles(),
            ));
        } catch (Throwable $observerException) {
            $this->logger?->warning('Telegram Bot observer failed.', [
                'method' => $method,
                'exception' => $observerException::class,
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildRequestOptions(TelegramBotRequestData $request): array
    {
        $options = [
            'timeout' => $this->config->timeout,
            'http_errors' => false,
        ];

        if (! $request->containsFiles()) {
            return array_merge($options, ['json' => $request->json()]);
        }

        return array_merge($options, ['multipart' => $request->multipart()]);
    }

    private function httpClient(): ClientInterface
    {
        return $this->httpClient ??= new Client([
            'timeout' => $this->config->timeout,
            'http_errors' => false,
        ]);
    }

    private function assertConfigured(): void
    {
        if ($this->config->token === null || trim($this->config->token) === '') {
            throw new TelegramBotConfigurationException('Telegram Bot token is not configured.');
        }
    }

    private function normalizeMethod(string|TelegramBotApiMethod $method): string
    {
        return $method instanceof TelegramBotApiMethod ? $method->value : $method;
    }

    private function assertMethodName(string $method): void
    {
        if (preg_match('/^\w+$/', $method) !== 1) {
            throw new InvalidArgumentException('Telegram Bot API method names may contain only letters, numbers, and underscores.');
        }
    }

    private function assertRequestMatchesMethod(TelegramBotRequestData $request, string $method): void
    {
        if (! $request instanceof TelegramBotMethodRequest || $request->method() === $method) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'Telegram Bot request DTO for method [%s] cannot be used with method [%s].',
            $request->method(),
            $method,
        ));
    }

    private function sanitizeTransportMessage(string $message): string
    {
        if ($this->config->token === null || $this->config->token === '') {
            return $message;
        }

        return str_replace(
            [$this->config->token, rawurlencode($this->config->token)],
            '<redacted-bot-token>',
            $message,
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function assertValidResponsePayload(array $payload, string $method): void
    {
        if (! array_key_exists('ok', $payload) || ! is_bool($payload['ok'])) {
            $this->logger?->error('Telegram Bot API response did not contain a boolean ok field.', [
                'method' => $method,
            ]);

            throw new TelegramBotTransportException('Telegram Bot API response did not contain a boolean ok field.');
        }

        if ($payload['ok'] && ! array_key_exists('result', $payload)) {
            $this->logger?->error('Telegram Bot API successful response did not contain a result field.', [
                'method' => $method,
            ]);

            throw new TelegramBotTransportException('Telegram Bot API successful response did not contain a result field.');
        }

        if (! $payload['ok'] && ! is_string($payload['description'] ?? null)) {
            $this->logger?->error('Telegram Bot API failed response did not contain a string description field.', [
                'method' => $method,
            ]);

            throw new TelegramBotTransportException('Telegram Bot API failed response did not contain a string description field.');
        }
    }
}
