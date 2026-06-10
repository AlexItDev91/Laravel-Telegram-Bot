<?php

namespace AlexItDev91\LaravelTelegramBot\DeepLinks;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramDeepLinkException;
use DateTimeInterface;
use InvalidArgumentException;
use JsonException;

final readonly class TelegramStartPayloadSigner
{
    private const string PREFIX = 's_';

    private const int SIGNATURE_HEX_LENGTH = 20;

    /**
     * @param  string|array<string, mixed>  $payload
     */
    public function sign(
        string|array $payload,
        string $secret,
        ?int $ttlSeconds = null,
        ?DateTimeInterface $now = null,
    ): string {
        $this->assertSecret($secret);

        if ($ttlSeconds !== null && $ttlSeconds <= 0) {
            throw new InvalidArgumentException('Telegram start payload TTL must be greater than zero.');
        }

        $body = ['d' => $payload];

        if ($ttlSeconds !== null) {
            $body['e'] = ($now?->getTimestamp() ?? time()) + $ttlSeconds;
        }

        $encoded = $this->base64UrlEncode(json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));
        $signature = $this->signature($encoded, $secret);
        $signedPayload = self::PREFIX.$encoded.'_'.$signature;

        TelegramDeepLink::assertPayload($signedPayload);

        return $signedPayload;
    }

    public function verify(
        string $signedPayload,
        string $secret,
        ?DateTimeInterface $now = null,
    ): TelegramSignedStartPayload {
        $this->assertSecret($secret);
        TelegramDeepLink::assertPayload($signedPayload);

        if (! str_starts_with($signedPayload, self::PREFIX)) {
            throw new TelegramDeepLinkException('Telegram start payload is not a signed payload.');
        }

        $bodyAndSignature = substr($signedPayload, strlen(self::PREFIX));
        $separator = strrpos($bodyAndSignature, '_');

        if ($separator === false) {
            throw new TelegramDeepLinkException('Telegram signed start payload is malformed.');
        }

        $encoded = substr($bodyAndSignature, 0, $separator);
        $signature = substr($bodyAndSignature, $separator + 1);

        if (! hash_equals($this->signature($encoded, $secret), $signature)) {
            throw new TelegramDeepLinkException('Telegram signed start payload signature is invalid.');
        }

        $body = $this->decodeBody($encoded);
        $payload = $body['d'] ?? null;

        if (! is_string($payload) && (! is_array($payload) || array_is_list($payload))) {
            throw new TelegramDeepLinkException('Telegram signed start payload body is invalid.');
        }

        $expiresAt = isset($body['e']) && is_int($body['e']) ? $body['e'] : null;
        $result = new TelegramSignedStartPayload(
            payload: $this->stringKeyedPayload($payload),
            expiresAt: $expiresAt,
        );

        if ($result->expired($now?->getTimestamp() ?? time())) {
            throw new TelegramDeepLinkException('Telegram signed start payload has expired.');
        }

        return $result;
    }

    private function assertSecret(string $secret): void
    {
        if (trim($secret) === '') {
            throw new InvalidArgumentException('Telegram start payload signing secret must not be empty.');
        }
    }

    private function signature(string $encodedPayload, string $secret): string
    {
        return substr(hash_hmac('sha256', $encodedPayload, $secret), 0, self::SIGNATURE_HEX_LENGTH);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeBody(string $encoded): array
    {
        $decoded = base64_decode(strtr($encoded, '-_', '+/').str_repeat('=', (4 - strlen($encoded) % 4) % 4), strict: true);

        if ($decoded === false) {
            throw new TelegramDeepLinkException('Telegram signed start payload body is not valid base64url.');
        }

        try {
            $body = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new TelegramDeepLinkException('Telegram signed start payload body is not valid JSON.');
        }

        if (! is_array($body) || array_is_list($body)) {
            throw new TelegramDeepLinkException('Telegram signed start payload body is invalid.');
        }

        return $body;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    /**
     * @param  string|array<string, mixed>  $payload
     * @return string|array<string, mixed>
     */
    private function stringKeyedPayload(string|array $payload): string|array
    {
        if (is_string($payload)) {
            return $payload;
        }

        $stringKeyed = [];

        foreach ($payload as $key => $value) {
            if (! is_string($key)) {
                throw new TelegramDeepLinkException('Telegram signed start payload object keys must be strings.');
            }

            $stringKeyed[$key] = $value;
        }

        return $stringKeyed;
    }
}
