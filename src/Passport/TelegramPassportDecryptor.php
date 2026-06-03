<?php

namespace AlexItDev91\LaravelTelegramBot\Passport;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramPassportDecryptionException;
use JsonException;

final class TelegramPassportDecryptor
{
    /**
     * @param  array<string, mixed>  $passportData
     * @return array{credentials: array<string, mixed>, nonce: string|null, elements: list<array<string, mixed>>}
     */
    public function decryptPassportData(
        array $passportData,
        string $privateKey,
        ?string $passphrase = null,
        ?string $expectedNonce = null,
    ): array {
        if (! is_array($passportData['credentials'] ?? null)) {
            throw new TelegramPassportDecryptionException('Telegram Passport payload does not contain encrypted credentials.');
        }

        $credentials = $this->decryptCredentials($passportData['credentials'], $privateKey, $passphrase);
        $nonce = isset($credentials['nonce']) && is_string($credentials['nonce']) ? $credentials['nonce'] : null;

        if ($expectedNonce !== null && ! hash_equals($expectedNonce, (string) $nonce)) {
            throw new TelegramPassportDecryptionException('Telegram Passport nonce does not match the expected value.');
        }

        $elements = [];

        foreach ($passportData['data'] ?? [] as $element) {
            if (! is_array($element)) {
                continue;
            }

            $type = $element['type'] ?? null;
            $decryptedElement = $element;

            if (is_string($type)) {
                $secureValue = $credentials['secure_data'][$type] ?? null;

                if (is_array($secureValue)) {
                    $decryptedElement['credentials'] = $secureValue;

                    if (isset($element['data']) && is_string($element['data']) && is_array($secureValue['data'] ?? null)) {
                        $decryptedElement['decrypted_data'] = $this->decryptElementData($element['data'], $secureValue['data']);
                    }
                }
            }

            $elements[] = $decryptedElement;
        }

        return [
            'credentials' => $credentials,
            'nonce' => $nonce,
            'elements' => $elements,
        ];
    }

    /**
     * @param  array<string, mixed>  $encryptedCredentials
     * @return array<string, mixed>
     */
    public function decryptCredentials(
        array $encryptedCredentials,
        string $privateKey,
        ?string $passphrase = null,
    ): array {
        $this->assertOpenSslAvailable();

        $secret = $this->decryptSecret($this->decodeBase64Field($encryptedCredentials, 'secret'), $privateKey, $passphrase);
        $hash = $this->decodeBase64Field($encryptedCredentials, 'hash');
        $encryptedData = $this->decodeBase64Field($encryptedCredentials, 'data');
        $paddedCredentials = $this->decryptValue($encryptedData, $secret, $hash);

        $this->assertHashMatches($paddedCredentials, $hash, 'Telegram Passport credentials hash mismatch.');

        return $this->decodeJson($this->removeTelegramPassportPadding($paddedCredentials));
    }

    /**
     * @param  array<string, mixed>  $dataCredentials
     * @return array<string, mixed>
     */
    public function decryptElementData(string $encryptedData, array $dataCredentials): array
    {
        $this->assertOpenSslAvailable();

        $secret = $this->decodeBase64Field($dataCredentials, 'secret');
        $hash = $this->decodeBase64Field($dataCredentials, 'data_hash');
        $paddedData = $this->decryptValue($this->decodeBase64String($encryptedData, 'data'), $secret, $hash);

        $this->assertHashMatches($paddedData, $hash, 'Telegram Passport data hash mismatch.');

        return $this->decodeJson($this->removeTelegramPassportPadding($paddedData));
    }

    /**
     * @param  array<string, mixed>  $fileCredentials
     */
    public function decryptFileContents(string $encryptedContents, array $fileCredentials): string
    {
        $this->assertOpenSslAvailable();

        $secret = $this->decodeBase64Field($fileCredentials, 'secret');
        $hash = $this->decodeBase64Field($fileCredentials, 'file_hash');
        $paddedContents = $this->decryptValue($encryptedContents, $secret, $hash);

        $this->assertHashMatches($paddedContents, $hash, 'Telegram Passport file hash mismatch.');

        return $this->removeTelegramPassportPadding($paddedContents);
    }

    private function decryptSecret(
        string $encryptedSecret,
        string $privateKey,
        ?string $passphrase,
    ): string {
        $key = openssl_pkey_get_private($privateKey, $passphrase);

        if ($key === false) {
            throw new TelegramPassportDecryptionException('Unable to load Telegram Passport private key.');
        }

        $decrypted = '';

        if (! openssl_private_decrypt($encryptedSecret, $decrypted, $key, OPENSSL_PKCS1_OAEP_PADDING)) {
            throw new TelegramPassportDecryptionException('Unable to decrypt Telegram Passport credentials secret.');
        }

        return $decrypted;
    }

    private function assertOpenSslAvailable(): void
    {
        if (! extension_loaded('openssl')) {
            throw new TelegramPassportDecryptionException('OpenSSL extension is required to decrypt Telegram Passport data.');
        }
    }

    private function decryptValue(string $encryptedValue, string $secret, string $hash): string
    {
        $keyMaterial = hash('sha512', $secret.$hash, true);
        $decrypted = openssl_decrypt(
            $encryptedValue,
            'aes-256-cbc',
            substr($keyMaterial, 0, 32),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            substr($keyMaterial, 32, 16),
        );

        if ($decrypted === false) {
            throw new TelegramPassportDecryptionException('Unable to decrypt Telegram Passport encrypted value.');
        }

        return $decrypted;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function decodeBase64Field(array $payload, string $field): string
    {
        if (! isset($payload[$field]) || ! is_string($payload[$field])) {
            throw new TelegramPassportDecryptionException("Telegram Passport field [$field] is missing or invalid.");
        }

        return $this->decodeBase64String($payload[$field], $field);
    }

    private function decodeBase64String(string $value, string $field): string
    {
        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            throw new TelegramPassportDecryptionException("Telegram Passport field [$field] is not valid base64.");
        }

        return $decoded;
    }

    private function assertHashMatches(string $paddedValue, string $expectedHash, string $message): void
    {
        if (! hash_equals($expectedHash, hash('sha256', $paddedValue, true))) {
            throw new TelegramPassportDecryptionException($message);
        }
    }

    private function removeTelegramPassportPadding(string $paddedValue): string
    {
        if ($paddedValue === '') {
            throw new TelegramPassportDecryptionException('Telegram Passport encrypted value is empty.');
        }

        $paddingLength = ord($paddedValue[0]);

        if ($paddingLength < 32 || $paddingLength > strlen($paddedValue)) {
            throw new TelegramPassportDecryptionException('Telegram Passport encrypted value has invalid padding.');
        }

        return substr($paddedValue, $paddingLength);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJson(string $json): array
    {
        try {
            $decoded = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new TelegramPassportDecryptionException('Telegram Passport decrypted JSON is invalid.', previous: $exception);
        }

        if (! is_array($decoded)) {
            throw new TelegramPassportDecryptionException('Telegram Passport decrypted JSON is not an object.');
        }

        return $decoded;
    }
}
