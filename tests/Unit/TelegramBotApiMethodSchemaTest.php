<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUpdatesRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendChatActionRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendPollRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\TelegramBotApiRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramChatAction;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramPollType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramUpdateType;
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

        $this->assertCount(180, $schema);

        foreach (TelegramBotApiMethod::cases() as $method) {
            $this->assertArrayHasKey($method->value, $schema);
        }

        $this->assertCount(884, array_merge(...array_values($schema)));
        $this->assertSame(64, strlen(TelegramBotApiMethodSchema::checksum()));
        $this->assertSame(['chat_id', 'text'], TelegramBotApiMethodSchema::requiredParameters(TelegramBotApiMethod::sendMessage));
        $this->assertSame(['chat_id', 'rich_message'], TelegramBotApiMethodSchema::requiredParameters(TelegramBotApiMethod::sendRichMessage));
        $this->assertSame([], TelegramBotApiMethodSchema::requiredParameters(TelegramBotApiMethod::editMessageText));
        $this->assertSame([], TelegramBotApiMethodSchema::parameters(TelegramBotApiMethod::getMe));
    }

    public function test_generated_request_registry_covers_every_method(): void
    {
        $requests = TelegramBotApiRequestRegistry::all();

        $this->assertCount(180, $requests);

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

    public function test_generated_request_builders_bind_known_string_parameters_to_enums(): void
    {
        $chatAction = SendChatActionRequestData::make(
            chatId: '123456789',
            action: TelegramChatAction::UploadPhoto,
        );
        $poll = SendPollRequestData::make(
            chatId: '123456789',
            question: 'Deploy?',
            options: [['text' => 'Yes'], ['text' => 'No']],
            type: TelegramPollType::Quiz,
        );
        $updates = GetUpdatesRequestData::make(
            allowedUpdates: [TelegramUpdateType::Message, TelegramUpdateType::GuestMessage, TelegramUpdateType::ManagedBot],
        );

        $this->assertSame('upload_photo', $chatAction->toArray()['action']);
        $this->assertSame('quiz', $poll->toArray()['type']);
        $this->assertSame(['message', 'guest_message', 'managed_bot'], $updates->toArray()['allowed_updates']);
    }

    public function test_update_type_enum_matches_webhook_update_detection_surface(): void
    {
        $enumTypes = array_map(
            static fn (TelegramUpdateType $type): string => $type->value,
            TelegramUpdateType::cases(),
        );

        sort($enumTypes);

        $webhookTypes = TelegramWebhookUpdate::updateTypes();
        sort($webhookTypes);

        $this->assertSame($webhookTypes, $enumTypes);
    }

    public function test_generated_result_schema_covers_every_method(): void
    {
        $results = TelegramBotApiResultSchema::all();

        $this->assertCount(180, $results);

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
        $this->assertSame('Boolean', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendChatAction));
        $this->assertSame('Boolean', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendChatJoinRequestWebApp));
        $this->assertSame('Boolean', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendGift));
        $this->assertSame('Boolean', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendMessageDraft));
        $this->assertSame('Message', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendRichMessage));
        $this->assertSame('Boolean', TelegramBotApiResultSchema::type(TelegramBotApiMethod::sendRichMessageDraft));
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

    public function test_generated_request_data_rejects_blank_required_parameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram Bot API method [sendMessage] requires parameter(s): chat_id.');

        SendMessageRequestData::make(chatId: '', text: 'Hello');
    }

    public function test_generated_request_data_validates_conditional_message_references(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram Bot API method [editMessageText] requires either [inline_message_id] or both [chat_id] and [message_id].');

        TelegramBotRequestData::forMethod(TelegramBotApiMethod::editMessageText, [
            'text' => 'Updated',
        ]);
    }

    public function test_generated_request_data_validates_non_zero_rich_message_draft_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram Bot API method [sendRichMessageDraft] requires parameter [draft_id] to be non-zero.');

        TelegramBotRequestData::forMethod(TelegramBotApiMethod::sendRichMessageDraft, [
            'chat_id' => 123456789,
            'draft_id' => 0,
            'rich_message' => ['html' => '<p>Thinking</p>'],
        ]);
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
