<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramDeepLink;
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramStartPayloadSigner;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramDeepLinkException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TelegramDeepLinkTest extends TestCase
{
    public function test_builds_bot_group_mini_app_and_attachment_links(): void
    {
        $this->assertSame('https://t.me/CompanyBot', TelegramDeepLink::bot('@CompanyBot')->url());
        $this->assertSame('https://t.me/CompanyBot?start=ref_42', TelegramDeepLink::start('CompanyBot', 'ref_42')->url());
        $this->assertSame('https://t.me/CompanyBot?startgroup=setup-42', TelegramDeepLink::startGroup('CompanyBot', 'setup-42')->url());
        $this->assertSame('https://t.me/CompanyBot?startapp=cart_42&mode=compact', TelegramDeepLink::startApp('CompanyBot', 'cart_42', compact: true)->url());
        $this->assertSame('https://t.me/CompanyBot/shop?startapp=cart_42', TelegramDeepLink::startApp('CompanyBot', 'cart_42', appName: 'shop')->url());
        $this->assertSame('https://t.me/CompanyBot?startattach=upload_42&choose=users+bots', TelegramDeepLink::startAttach('CompanyBot', 'upload_42', ['users', 'bots'])->url());
        $this->assertSame('https://t.me/CompanyBot?startapp', (string) TelegramDeepLink::startApp('CompanyBot'));
    }

    public function test_rejects_invalid_or_oversized_payloads(): void
    {
        $this->expectException(TelegramDeepLinkException::class);
        $this->expectExceptionMessage('exceeds the 64-character limit');

        TelegramDeepLink::start('CompanyBot', str_repeat('a', 65));
    }

    public function test_signs_and_verifies_short_start_payloads(): void
    {
        $signer = new TelegramStartPayloadSigner();

        $signedPayload = $signer->sign(
            payload: 'ref42',
            secret: 'application-secret',
            ttlSeconds: 300,
            now: new DateTimeImmutable('@1700000000'),
        );

        $this->assertMatchesRegularExpression('/\As_[A-Za-z0-9_-]+_[a-f0-9]{20}\z/', $signedPayload);
        $this->assertLessThanOrEqual(64, strlen($signedPayload));
        $this->assertSame(
            'https://t.me/CompanyBot?start='.$signedPayload,
            TelegramDeepLink::start('CompanyBot', $signedPayload)->url(),
        );

        $verified = $signer->verify(
            signedPayload: $signedPayload,
            secret: 'application-secret',
            now: new DateTimeImmutable('@1700000060'),
        );

        $this->assertSame('ref42', $verified->string());
        $this->assertNull($verified->array());
        $this->assertSame(1700000300, $verified->expiresAt());
        $this->assertSame([
            'payload' => 'ref42',
            'expires_at' => 1700000300,
        ], $verified->toArray());
    }

    public function test_signs_and_verifies_small_array_payloads_without_expiry(): void
    {
        $signer = new TelegramStartPayloadSigner();

        $signedPayload = $signer->sign(['r' => '42'], 'application-secret');
        $verified = $signer->verify($signedPayload, 'application-secret');

        $this->assertSame(['r' => '42'], $verified->array());
        $this->assertSame(['r' => '42'], $verified->payload());
        $this->assertNull($verified->expiresAt());
    }

    public function test_rejects_tampered_expired_and_oversized_signed_payloads(): void
    {
        $signer = new TelegramStartPayloadSigner();
        $signedPayload = $signer->sign(
            payload: 'ref42',
            secret: 'application-secret',
            ttlSeconds: 60,
            now: new DateTimeImmutable('@1700000000'),
        );

        try {
            $signer->verify($signedPayload, 'other-secret', new DateTimeImmutable('@1700000001'));
            $this->fail('Expected invalid signature to be rejected.');
        } catch (TelegramDeepLinkException $exception) {
            $this->assertStringContainsString('signature is invalid', $exception->getMessage());
        }

        try {
            $signer->verify($signedPayload, 'application-secret', new DateTimeImmutable('@1700000061'));
            $this->fail('Expected expired payload to be rejected.');
        } catch (TelegramDeepLinkException $exception) {
            $this->assertStringContainsString('has expired', $exception->getMessage());
        }

        $this->expectException(TelegramDeepLinkException::class);
        $this->expectExceptionMessage('exceeds the 64-character limit');

        $signer->sign(str_repeat('a', 40), 'application-secret', ttlSeconds: 300, now: new DateTimeImmutable('@1700000000'));
    }
}
