<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramMiniAppInitDataException;
use AlexItDev91\LaravelTelegramBot\MiniApps\TelegramMiniAppInitDataValidator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TelegramMiniAppInitDataValidatorTest extends TestCase
{
    public function test_validates_signed_init_data_and_exposes_typed_accessors(): void
    {
        $validator = new TelegramMiniAppInitDataValidator();
        $token = '123456:test-token';
        $initData = $this->signedInitData([
            'auth_date' => '1700000000',
            'can_send_after' => '15',
            'chat' => json_encode([
                'id' => -1001234567890,
                'type' => 'supergroup',
                'title' => 'Support',
                'username' => 'support_chat',
                'photo_url' => 'https://example.test/chat.jpg',
            ], JSON_THROW_ON_ERROR),
            'chat_instance' => 'chat-instance',
            'chat_type' => 'supergroup',
            'query_id' => 'AAHdF6IQAAAAAN0XohDhrOrc',
            'start_param' => 'campaign_42',
            'user' => json_encode([
                'id' => 9007199254740991,
                'first_name' => 'Alex',
                'last_name' => 'Tester',
                'username' => 'alex_tester',
                'language_code' => 'en',
                'is_premium' => true,
                'added_to_attachment_menu' => true,
                'allows_write_to_pm' => false,
                'photo_url' => 'https://example.test/user.jpg',
            ], JSON_THROW_ON_ERROR),
        ], $token);

        $data = $validator->validate(
            initData: $initData,
            botToken: $token,
            maxAgeSeconds: 120,
            now: new DateTimeImmutable('@1700000060'),
        );

        $this->assertSame($initData, $data->raw());
        $this->assertSame('AAHdF6IQAAAAAN0XohDhrOrc', $data->queryId());
        $this->assertSame('supergroup', $data->chatType());
        $this->assertSame('chat-instance', $data->chatInstance());
        $this->assertSame('campaign_42', $data->startParam());
        $this->assertSame(15, $data->canSendAfter());
        $this->assertSame(1700000000, $data->authDate());
        $this->assertSame(9007199254740991, $data->user()?->id());
        $this->assertSame('Alex', $data->user()?->firstName());
        $this->assertSame('Tester', $data->user()?->lastName());
        $this->assertSame('alex_tester', $data->user()?->username());
        $this->assertSame('en', $data->user()?->languageCode());
        $this->assertTrue($data->user()?->isPremium());
        $this->assertTrue($data->user()?->addedToAttachmentMenu());
        $this->assertFalse($data->user()?->allowsWriteToPm());
        $this->assertSame('https://example.test/user.jpg', $data->user()?->photoUrl());
        $this->assertSame(-1001234567890, $data->chat()?->id());
        $this->assertSame('supergroup', $data->chat()?->type());
        $this->assertSame('Support', $data->chat()?->title());
        $this->assertSame('support_chat', $data->chat()?->username());
        $this->assertSame('https://example.test/chat.jpg', $data->chat()?->photoUrl());
        $this->assertSame('campaign_42', $data->toArray()['start_param']);
    }

    public function test_rejects_tampered_init_data(): void
    {
        $validator = new TelegramMiniAppInitDataValidator();
        $initData = $this->signedInitData([
            'auth_date' => '1700000000',
            'query_id' => 'original',
        ], '123456:test-token');
        $tampered = str_replace('query_id=original', 'query_id=changed', $initData);

        $this->expectException(TelegramMiniAppInitDataException::class);
        $this->expectExceptionMessage('signature is invalid');

        $validator->validate($tampered, '123456:test-token');
    }

    public function test_reports_invalid_data_without_throwing(): void
    {
        $validator = new TelegramMiniAppInitDataValidator();

        $this->assertFalse($validator->isValid('auth_date=1700000000&hash=bad', '123456:test-token'));
    }

    public function test_rejects_expired_init_data(): void
    {
        $validator = new TelegramMiniAppInitDataValidator();
        $initData = $this->signedInitData([
            'auth_date' => '1700000000',
            'query_id' => 'AAHdF6IQAAAAAN0XohDhrOrc',
        ], '123456:test-token');

        $this->expectException(TelegramMiniAppInitDataException::class);
        $this->expectExceptionMessage('auth_date is too old');

        $validator->validate(
            initData: $initData,
            botToken: '123456:test-token',
            maxAgeSeconds: 60,
            now: new DateTimeImmutable('@1700000061'),
        );
    }

    public function test_rejects_malformed_query_strings_and_duplicate_fields(): void
    {
        $validator = new TelegramMiniAppInitDataValidator();

        try {
            $validator->validate('auth_date=1700000000&broken', '123456:test-token');
            $this->fail('Expected malformed query string to be rejected.');
        } catch (TelegramMiniAppInitDataException $exception) {
            $this->assertStringContainsString('valid query string', $exception->getMessage());
        }

        $initData = $this->signedInitData([
            'auth_date' => '1700000000',
            'query_id' => 'AAHdF6IQAAAAAN0XohDhrOrc',
        ], '123456:test-token');

        $this->expectException(TelegramMiniAppInitDataException::class);
        $this->expectExceptionMessage('duplicate field [query_id]');

        $validator->validate($initData.'&query_id=duplicate', '123456:test-token');
    }

    /**
     * @param  array<string, string>  $fields
     */
    private function signedInitData(array $fields, string $token): string
    {
        ksort($fields, SORT_STRING);

        $lines = [];

        foreach ($fields as $key => $value) {
            $lines[] = $key.'='.$value;
        }

        $secretKey = hash_hmac('sha256', $token, 'WebAppData', binary: true);
        $hash = hash_hmac('sha256', implode("\n", $lines), $secretKey);

        return http_build_query(array_merge($fields, ['hash' => $hash]), '', '&', PHP_QUERY_RFC3986);
    }
}
