<?php

namespace AlexItDev91\LaravelTelegramBot\MiniApps;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramMiniAppInitDataException;
use DateTimeInterface;
use InvalidArgumentException;

final readonly class TelegramMiniAppInitDataValidator
{
    public function validate(
        string $initData,
        string $botToken,
        ?int $maxAgeSeconds = null,
        ?DateTimeInterface $now = null,
    ): TelegramMiniAppInitData {
        $this->assertValidOptions($botToken, $maxAgeSeconds);

        $fields = $this->parseFields($initData);
        $this->assertValidHash($fields);
        $this->assertValidSignature($fields, $botToken);
        $this->assertValidAuthDate($fields, $maxAgeSeconds, $now);

        return TelegramMiniAppInitData::fromFields($initData, $fields);
    }

    public function isValid(
        string $initData,
        string $botToken,
        ?int $maxAgeSeconds = null,
        ?DateTimeInterface $now = null,
    ): bool {
        try {
            $this->validate($initData, $botToken, $maxAgeSeconds, $now);
        } catch (InvalidArgumentException|TelegramMiniAppInitDataException) {
            return false;
        }

        return true;
    }

    private function assertValidOptions(string $botToken, ?int $maxAgeSeconds): void
    {
        if (trim($botToken) === '') {
            throw new InvalidArgumentException('Telegram Mini App bot token must not be empty.');
        }

        if ($maxAgeSeconds !== null && $maxAgeSeconds <= 0) {
            throw new InvalidArgumentException('Telegram Mini App init data max age must be greater than zero.');
        }
    }

    /**
     * @return array<string, string>
     */
    private function parseFields(string $initData): array
    {
        if (trim($initData) === '') {
            throw new TelegramMiniAppInitDataException('Telegram Mini App init data must not be empty.');
        }

        $fields = [];

        foreach (explode('&', $initData) as $pair) {
            if ($pair === '' || ! str_contains($pair, '=')) {
                throw new TelegramMiniAppInitDataException('Telegram Mini App init data must be a valid query string.');
            }

            [$rawKey, $rawValue] = explode('=', $pair, 2);
            $key = urldecode($rawKey);

            if ($key === '') {
                throw new TelegramMiniAppInitDataException('Telegram Mini App init data contains an empty field name.');
            }

            if (array_key_exists($key, $fields)) {
                throw new TelegramMiniAppInitDataException("Telegram Mini App init data contains duplicate field [$key].");
            }

            $fields[$key] = urldecode($rawValue);
        }

        return $fields;
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function assertValidHash(array $fields): void
    {
        $hash = $fields['hash'] ?? null;

        if ($hash === null || $hash === '') {
            throw new TelegramMiniAppInitDataException('Telegram Mini App init data is missing the hash field.');
        }

        if (preg_match('/\A[a-f0-9]{64}\z/i', $hash) !== 1) {
            throw new TelegramMiniAppInitDataException('Telegram Mini App init data hash is not a valid SHA-256 hex digest.');
        }
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function assertValidSignature(array $fields, string $botToken): void
    {
        $dataCheckString = $this->dataCheckString($fields);
        $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', binary: true);
        $calculatedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (! hash_equals($calculatedHash, strtolower($fields['hash']))) {
            throw new TelegramMiniAppInitDataException('Telegram Mini App init data signature is invalid.');
        }
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function assertValidAuthDate(
        array $fields,
        ?int $maxAgeSeconds,
        ?DateTimeInterface $now,
    ): void {
        $authDate = $fields['auth_date'] ?? null;

        if ($authDate === null || ! ctype_digit($authDate)) {
            throw new TelegramMiniAppInitDataException('Telegram Mini App init data auth_date must be present as a Unix timestamp.');
        }

        if ($maxAgeSeconds === null) {
            return;
        }

        $nowTimestamp = $now?->getTimestamp() ?? time();

        if ($nowTimestamp - (int) $authDate > $maxAgeSeconds) {
            throw new TelegramMiniAppInitDataException('Telegram Mini App init data auth_date is too old.');
        }
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function dataCheckString(array $fields): string
    {
        unset($fields['hash']);
        ksort($fields, SORT_STRING);

        $lines = [];

        foreach ($fields as $key => $value) {
            $lines[] = $key.'='.$value;
        }

        return implode("\n", $lines);
    }
}
