<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\TelegramBotApiRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiRequestRegistry;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiResultSchema;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodSchema;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TelegramBotApiMethodSchemaTest extends TestCase
{
    public function test_schema_covers_every_registered_method_and_documented_parameter(): void
    {
        $schema = TelegramBotApiMethodSchema::all();

        $this->assertCount(176, $schema);

        foreach (TelegramBotApiMethod::cases() as $method) {
            $this->assertArrayHasKey($method->value, $schema);
        }

        $this->assertCount(863, array_merge(...array_values($schema)));
        $this->assertSame(64, strlen(TelegramBotApiMethodSchema::checksum()));
        $this->assertSame(['chat_id', 'text'], TelegramBotApiMethodSchema::requiredParameters(TelegramBotApiMethod::sendMessage));
        $this->assertSame([], TelegramBotApiMethodSchema::parameters(TelegramBotApiMethod::getMe));
    }

    public function test_generated_request_registry_covers_every_method(): void
    {
        $requests = TelegramBotApiRequestRegistry::all();

        $this->assertCount(176, $requests);

        foreach (TelegramBotApiMethod::cases() as $method) {
            $requestClass = TelegramBotApiRequestRegistry::requestClass($method);

            $this->assertIsString($requestClass);
            $this->assertTrue(is_subclass_of($requestClass, TelegramBotApiRequestData::class));
            $this->assertSame($method->value, $requestClass::METHOD);
        }
    }

    public function test_generated_request_builders_are_method_scoped_and_ide_friendly(): void
    {
        $request = SendMessageRequestData::make(
            chatId: '123456789',
            text: 'Hello',
            parseMode: TelegramParseMode::HTML,
            extra: ['future_optional_field' => 'kept'],
        );

        $this->assertSame('sendMessage', $request->method());
        $this->assertSame([
            'chat_id' => '123456789',
            'text' => 'Hello',
            'parse_mode' => 'HTML',
            'future_optional_field' => 'kept',
        ], $request->toArray());
        $this->assertSame('changed', $request->with('text', 'changed')->toArray()['text']);
        $this->assertFalse($request->withoutRequiredValidation()->validatesRequiredParameters());
    }

    public function test_generated_result_schema_covers_every_method(): void
    {
        $results = TelegramBotApiResultSchema::all();

        $this->assertCount(176, $results);

        foreach (TelegramBotApiMethod::cases() as $method) {
            $this->assertArrayHasKey($method->value, $results);
            $this->assertIsString(TelegramBotApiResultSchema::type($method));
        }

        $this->assertSame('Message', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendMessage));
        $this->assertSame('Array<Update>', TelegramBotApiResultSchema::type(TelegramBotApiMethod::getUpdates));
        $this->assertSame('String', TelegramBotApiResultSchema::type(TelegramBotApiMethod::createInvoiceLink));
        $this->assertSame('MessageId', TelegramBotApiResultSchema::type(TelegramBotApiMethod::copyMessage));
        $this->assertSame('Array<MessageId>', TelegramBotApiResultSchema::type(TelegramBotApiMethod::copyMessages));
        $this->assertSame('Poll', TelegramBotApiResultSchema::type(TelegramBotApiMethod::stopPoll));
        $this->assertTrue(TelegramBotApiResultSchema::allowsBool(TelegramBotApiMethod::editMessageText));
        $this->assertTrue(TelegramBotApiResultSchema::allowsBool(TelegramBotApiMethod::setGameScore));
        $this->assertFalse(TelegramBotApiResultSchema::allowsBool(TelegramBotApiMethod::editMessageChecklist));
    }

    public function test_method_request_data_validates_required_parameters(): void
    {
        $request = TelegramBotRequestData::forMethod(TelegramBotApiMethod::sendMessage, [
            'chat_id' => '123456789',
            'text' => 'Hello',
            'future_optional_field' => 'kept',
        ]);

        $this->assertInstanceOf(TelegramBotMethodRequestData::class, $request);
        $this->assertSame('sendMessage', $request->method());
        $this->assertSame('kept', $request->toArray()['future_optional_field']);
        $this->assertSame(['chat_id', 'text'], $request->requiredParameters());
    }

    public function test_method_request_data_can_defer_required_validation_for_channel_defaults(): void
    {
        $request = TelegramBotRequestData::forMethod(
            TelegramBotApiMethod::sendMessage,
            ['text' => 'Hello'],
            validateRequiredParameters: false,
        );

        $this->assertFalse($request->validatesRequiredParameters());
    }

    public function test_method_request_data_rejects_missing_required_parameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram Bot API method [sendMessage] requires parameter(s): chat_id.');

        TelegramBotRequestData::forMethod(TelegramBotApiMethod::sendMessage, ['text' => 'Hello']);
    }

    public function test_client_rejects_method_scoped_request_data_used_with_another_method(): void
    {
        $client = TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: new Client([
                'handler' => new MockHandler([
                    new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
                ]),
                'http_errors' => false,
            ]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('request DTO for method [getMe] cannot be used with method [sendMessage]');

        $client->sendMessage(TelegramBotRequestData::forMethod(TelegramBotApiMethod::getMe));
    }
}
