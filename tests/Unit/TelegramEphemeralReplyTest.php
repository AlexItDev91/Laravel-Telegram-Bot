<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\EphemeralMessageParameters;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\ReplyParameters;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendDocumentData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendPhotoData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendContactRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichText;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookReply;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use AlexItDev91\LaravelTelegramBot\TelegramBot;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Testing\TelegramBotFake;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TelegramEphemeralReplyTest extends TestCase
{
    public function test_generated_and_generic_requests_allow_callback_only_destinations(): void
    {
        $destination = EphemeralMessageParameters::forCallbackQuery('callback-123')
            ->replaceCallbackQueryMessage(false);

        $request = SendContactRequestData::make(
            phoneNumber: '+12025550100',
            firstName: 'Test',
            ephemeralMessageParameters: $destination,
        );

        $this->assertSame([
            'phone_number' => '+12025550100',
            'first_name' => 'Test',
            'ephemeral_message_parameters' => [
                'callback_query_id' => 'callback-123',
                'replace_callback_query_message' => false,
            ],
        ], $request->toArray());
        $this->assertSame($request->toArray(), TelegramBotRequestData::forMethod('sendContact', $request->toArray())->toArray());
    }

    public function test_generated_requests_keep_the_content_required_for_callback_only_destinations(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires parameter(s): text.');

        SendMessageRequestData::make(ephemeralMessageParameters: ['callback_query_id' => 'callback-123']);
    }

    public function test_a_callback_with_an_explicit_receiver_still_requires_a_chat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires parameter(s): chat_id.');

        SendMessageRequestData::make(
            text: 'Private response',
            ephemeralMessageParameters: EphemeralMessageParameters::forUser('1234567890123')->fromCallbackQuery('callback-123'),
        );
    }

    public function test_handwritten_requests_accept_typed_parameters_and_keep_legacy_extra_fields(): void
    {
        $destination = EphemeralMessageParameters::forCallbackQuery('callback-123');
        $text = new SendMessageData(text: 'Private response', ephemeralMessageParameters: $destination);
        $photo = new SendPhotoData(photo: 'photo-file', ephemeralMessageParameters: $destination);
        $document = new SendDocumentData(document: 'document-file', ephemeralMessageParameters: $destination);

        $expected = ['ephemeral_message_parameters' => ['callback_query_id' => 'callback-123']];
        $this->assertSame(['text' => 'Private response'] + $expected, $text->toArray());
        $this->assertSame(['photo' => 'photo-file'] + $expected, $photo->toArray());
        $this->assertSame(['document' => 'document-file'] + $expected, $document->toArray());
        $this->assertSame(
            ['chat_id' => '1234567890123', 'text' => 'Private response'] + $expected,
            new SendMessageData('1234567890123', 'Private response', extra: $expected)->toArray(),
        );
    }

    public function test_callback_only_fluent_replies_send_the_same_payload_through_http_and_webhooks(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], '{"ok":true,"result":{"message_id":42}}'),
        ]));
        $handler->push(Middleware::history($history));
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: new Client(['handler' => $handler]),
        );
        $message = TelegramMessage::text('Private response')
            ->ephemeralMessageParameters(EphemeralMessageParameters::forCallbackQuery('callback-123'));

        $this->assertSame(['message_id' => 42], $client->send($message));
        $payload = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame([
            'text' => 'Private response',
            'ephemeral_message_parameters' => ['callback_query_id' => 'callback-123'],
        ], $payload);
        $this->assertSame(['method' => 'sendMessage'] + $payload, TelegramWebhookReply::send($message)->toArray());
    }

    public function test_the_default_bot_and_fake_record_callback_only_replies(): void
    {
        $fake = new TelegramBotFake;
        $bot = new TelegramBot($fake);
        $reply = TelegramMessage::photo('photo-file')->ephemeralMessageParameters(['callback_query_id' => 'callback-123']);

        $bot->send($reply);
        $fake->send($reply);

        $this->assertCount(2, $fake->calls());
        foreach ($fake->calls() as $call) {
            $this->assertSame('sendPhoto', $call['method']);
            $this->assertSame([
                'photo' => 'photo-file',
                'ephemeral_message_parameters' => ['callback_query_id' => 'callback-123'],
            ], $call['parameters']);
        }
    }

    public function test_reply_parameters_keep_rich_quote_parts_in_order(): void
    {
        $quote = ['Opening', ['type' => 'bold', 'text' => ['inside ', ['type' => 'italic', 'text' => 'nested']]]];
        $payload = new ReplyParameters(
            messageId: 42,
            quoteParseMode: TelegramParseMode::HTML,
            quotePosition: 7,
            quoteRichTexts: ['Opening', RichText::bold(['inside ', RichText::italic('nested')])],
        )->toArray();

        $this->assertSame($quote, $payload['quote_rich_texts']);
        $this->assertSame(7, $payload['quote_position']);
        $this->assertSame($quote, new ReplyParameters(42, extra: ['quote_rich_texts' => $quote])->toArray()['quote_rich_texts']);
    }
}
