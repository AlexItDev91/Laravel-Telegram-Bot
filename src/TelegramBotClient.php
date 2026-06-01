<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramApiResponseData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotConfigurationException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotTransportException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class TelegramBotClient implements TelegramBotClientContract
{
    use TelegramBotApiMethods;

    public function __construct(
        private readonly TelegramBotConfigData $config,
        private ?ClientInterface $httpClient = null,
    ) {
        //
    }

    public static function make(?string $token, string $apiUrl = 'https://api.telegram.org', float $timeout = 10.0, ?ClientInterface $httpClient = null): self
    {
        return new self(
            config: new TelegramBotConfigData($token, $apiUrl, $timeout),
            httpClient: $httpClient,
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $this->assertConfigured();
        $method = $this->normalizeMethod($method);
        $this->assertMethodName($method);

        $request = $parameters instanceof TelegramBotRequestData
            ? $parameters
            : TelegramBotRequestData::fromArray($parameters);

        try {
            $response = $this->httpClient()->request(
                'POST',
                sprintf('%s/bot%s/%s', rtrim($this->config->apiUrl, '/'), $this->config->token, $method),
                $this->buildRequestOptions($request),
            );
        } catch (GuzzleException $exception) {
            throw new TelegramBotTransportException($exception->getMessage(), previous: $exception);
        }

        $payload = json_decode((string) $response->getBody(), true);

        if (! is_array($payload)) {
            throw new TelegramBotTransportException('Telegram Bot API returned a non-JSON response.');
        }

        $this->assertValidResponsePayload($payload);

        $apiResponse = TelegramApiResponseData::fromPayload($payload);

        if (! $apiResponse->ok) {
            throw new TelegramBotApiException(
                $apiResponse->description ?? 'Telegram Bot API request failed.',
                $apiResponse->errorCode,
                $apiResponse->parameters ?? [],
            );
        }

        return $apiResponse->result;
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
        if (preg_match('/^[A-Za-z0-9_]+$/', $method) !== 1) {
            throw new InvalidArgumentException('Telegram Bot API method names may contain only letters, numbers, and underscores.');
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function assertValidResponsePayload(array $payload): void
    {
        if (! array_key_exists('ok', $payload) || ! is_bool($payload['ok'])) {
            throw new TelegramBotTransportException('Telegram Bot API response did not contain a boolean ok field.');
        }

        if ($payload['ok'] && ! array_key_exists('result', $payload)) {
            throw new TelegramBotTransportException('Telegram Bot API successful response did not contain a result field.');
        }

        if (! $payload['ok'] && ! is_string($payload['description'] ?? null)) {
            throw new TelegramBotTransportException('Telegram Bot API failed response did not contain a string description field.');
        }
    }
}
