<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportAuthorizationRequest;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportElementError;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScope;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScopeElement;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\SetPassportDataErrorsData;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramPassportDecryptionException;
use AlexItDev91\LaravelTelegramBot\Passport\TelegramPassportDecryptor;
use PHPUnit\Framework\TestCase;

class TelegramPassportApiTest extends TestCase
{
    public function test_passport_error_request_data_covers_official_error_sources(): void
    {
        $request = new SetPassportDataErrorsData('9007199254740991', [
            PassportElementError::dataField('personal_details', 'first_name', 'data-hash', 'Fix first name'),
            PassportElementError::frontSide('passport', 'front-hash', 'Upload a clearer front side'),
            PassportElementError::reverseSide('driver_license', 'reverse-hash', 'Upload a clearer reverse side'),
            PassportElementError::selfie('identity_card', 'selfie-hash', 'Upload a clearer selfie'),
            PassportElementError::file('utility_bill', 'file-hash', 'Upload a clearer scan'),
            PassportElementError::files('bank_statement', ['file-hash-1', 'file-hash-2'], 'Upload all pages'),
            PassportElementError::translationFile('passport', 'translation-hash', 'Upload a clearer translation'),
            PassportElementError::translationFiles('rental_agreement', ['translation-hash-1'], 'Upload all translations'),
            PassportElementError::unspecified('address', 'element-hash', 'Address can not be verified'),
        ]);

        $this->assertSame([
            'user_id' => '9007199254740991',
            'errors' => [
                ['source' => 'data', 'type' => 'personal_details', 'field_name' => 'first_name', 'data_hash' => 'data-hash', 'message' => 'Fix first name'],
                ['source' => 'front_side', 'type' => 'passport', 'file_hash' => 'front-hash', 'message' => 'Upload a clearer front side'],
                ['source' => 'reverse_side', 'type' => 'driver_license', 'file_hash' => 'reverse-hash', 'message' => 'Upload a clearer reverse side'],
                ['source' => 'selfie', 'type' => 'identity_card', 'file_hash' => 'selfie-hash', 'message' => 'Upload a clearer selfie'],
                ['source' => 'file', 'type' => 'utility_bill', 'file_hash' => 'file-hash', 'message' => 'Upload a clearer scan'],
                ['source' => 'files', 'type' => 'bank_statement', 'file_hashes' => ['file-hash-1', 'file-hash-2'], 'message' => 'Upload all pages'],
                ['source' => 'translation_file', 'type' => 'passport', 'file_hash' => 'translation-hash', 'message' => 'Upload a clearer translation'],
                ['source' => 'translation_files', 'type' => 'rental_agreement', 'file_hashes' => ['translation-hash-1'], 'message' => 'Upload all translations'],
                ['source' => 'unspecified', 'type' => 'address', 'element_hash' => 'element-hash', 'message' => 'Address can not be verified'],
            ],
        ], $request->json());
    }

    public function test_passport_authorization_request_serializes_scope(): void
    {
        $request = new PassportAuthorizationRequest(
            botId: '9007199254740991',
            scope: new PassportScope([
                PassportScopeElement::one('personal_details', nativeNames: true),
                PassportScopeElement::oneOfSeveral(['passport', 'identity_card'], selfie: true, translation: true),
                'email',
            ]),
            publicKey: '-----BEGIN PUBLIC KEY-----test-----END PUBLIC KEY-----',
            nonce: 'nonce-123',
        );

        $this->assertSame([
            'bot_id' => '9007199254740991',
            'scope' => [
                'v' => 1,
                'data' => [
                    ['type' => 'personal_details', 'native_names' => true],
                    ['one_of' => ['passport', 'identity_card'], 'selfie' => true, 'translation' => true],
                    'email',
                ],
            ],
            'public_key' => '-----BEGIN PUBLIC KEY-----test-----END PUBLIC KEY-----',
            'nonce' => 'nonce-123',
        ], $request->toArray());
    }

    public function test_decrypts_passport_credentials_element_data_and_files(): void
    {
        if (! extension_loaded('openssl')) {
            $this->markTestSkipped('OpenSSL extension is required for Telegram Passport decryption.');
        }

        $keyPair = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $this->assertNotFalse($keyPair);
        $this->assertTrue(openssl_pkey_export($keyPair, $privateKeyPem));

        $keyDetails = openssl_pkey_get_details($keyPair);
        $this->assertIsArray($keyDetails);
        $publicKeyPem = $keyDetails['key'];

        $elementSecret = random_bytes(32);
        $encryptedElement = $this->encryptPassportValue(json_encode([
            'first_name' => 'Alex',
            'last_name' => 'Example',
        ], JSON_THROW_ON_ERROR), $elementSecret);

        $fileSecret = random_bytes(32);
        $encryptedFile = $this->encryptPassportValue('jpeg-binary-contents', $fileSecret);

        $credentialsSecret = random_bytes(32);
        $encryptedCredentialsData = $this->encryptPassportValue(json_encode([
            'secure_data' => [
                'personal_details' => [
                    'data' => [
                        'data_hash' => base64_encode($encryptedElement['hash']),
                        'secret' => base64_encode($elementSecret),
                    ],
                    'front_side' => [
                        'file_hash' => base64_encode($encryptedFile['hash']),
                        'secret' => base64_encode($fileSecret),
                    ],
                ],
            ],
            'nonce' => 'nonce-123',
        ], JSON_THROW_ON_ERROR), $credentialsSecret);

        $this->assertTrue(openssl_public_encrypt($credentialsSecret, $encryptedCredentialsSecret, $publicKeyPem, OPENSSL_PKCS1_OAEP_PADDING));

        $decryptor = new TelegramPassportDecryptor();
        $result = $decryptor->decryptPassportData([
            'data' => [
                [
                    'type' => 'personal_details',
                    'data' => base64_encode($encryptedElement['encrypted']),
                    'hash' => base64_encode(hash('sha256', 'element-hash', true)),
                ],
            ],
            'credentials' => [
                'data' => base64_encode($encryptedCredentialsData['encrypted']),
                'hash' => base64_encode($encryptedCredentialsData['hash']),
                'secret' => base64_encode($encryptedCredentialsSecret),
            ],
        ], $privateKeyPem, expectedNonce: 'nonce-123');

        $this->assertSame('nonce-123', $result['nonce']);
        $this->assertSame('Alex', $result['elements'][0]['decrypted_data']['first_name']);
        $this->assertSame('Example', $result['elements'][0]['decrypted_data']['last_name']);
        $this->assertSame('jpeg-binary-contents', $decryptor->decryptFileContents($encryptedFile['encrypted'], [
            'file_hash' => base64_encode($encryptedFile['hash']),
            'secret' => base64_encode($fileSecret),
        ]));
    }

    public function test_decryption_rejects_unexpected_nonce(): void
    {
        if (! extension_loaded('openssl')) {
            $this->markTestSkipped('OpenSSL extension is required for Telegram Passport decryption.');
        }

        $keyPair = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $this->assertNotFalse($keyPair);
        $this->assertTrue(openssl_pkey_export($keyPair, $privateKeyPem));

        $keyDetails = openssl_pkey_get_details($keyPair);
        $this->assertIsArray($keyDetails);

        $credentialsSecret = random_bytes(32);
        $encryptedCredentialsData = $this->encryptPassportValue(json_encode([
            'secure_data' => [],
            'nonce' => 'actual',
        ], JSON_THROW_ON_ERROR), $credentialsSecret);

        $this->assertTrue(openssl_public_encrypt($credentialsSecret, $encryptedCredentialsSecret, $keyDetails['key'], OPENSSL_PKCS1_OAEP_PADDING));

        $this->expectException(TelegramPassportDecryptionException::class);
        $this->expectExceptionMessage('Telegram Passport nonce does not match the expected value.');

        (new TelegramPassportDecryptor())->decryptPassportData([
            'data' => [],
            'credentials' => [
                'data' => base64_encode($encryptedCredentialsData['encrypted']),
                'hash' => base64_encode($encryptedCredentialsData['hash']),
                'secret' => base64_encode($encryptedCredentialsSecret),
            ],
        ], $privateKeyPem, expectedNonce: 'expected');
    }

    /**
     * @return array{encrypted: string, hash: string}
     */
    private function encryptPassportValue(string $value, string $secret): array
    {
        $padded = $this->addTelegramPassportPadding($value);
        $hash = hash('sha256', $padded, true);
        $keyMaterial = hash('sha512', $secret.$hash, true);
        $encrypted = openssl_encrypt(
            $padded,
            'aes-256-cbc',
            substr($keyMaterial, 0, 32),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            substr($keyMaterial, 32, 16),
        );

        $this->assertIsString($encrypted);

        return [
            'encrypted' => $encrypted,
            'hash' => $hash,
        ];
    }

    private function addTelegramPassportPadding(string $value): string
    {
        $paddingLength = 32;

        while (($paddingLength + strlen($value)) % 16 !== 0) {
            $paddingLength++;
        }

        return chr($paddingLength).str_repeat("\0", $paddingLength - 1).$value;
    }
}
