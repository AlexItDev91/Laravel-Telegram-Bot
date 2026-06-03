<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatMemberData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotResultData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramFileData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramBotTypedResponseTest extends TestCase
{
    public function test_get_me_data_returns_typed_user(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                'id' => 123456789,
                'is_bot' => true,
                'first_name' => 'Support Bot',
                'username' => 'support_bot',
                'supports_guest_queries' => true,
            ],
        ]);

        $user = $client->getMeData();

        $this->assertInstanceOf(TelegramUserData::class, $user);
        $this->assertSame(123456789, $user->id());
        $this->assertTrue($user->isBot());
        $this->assertSame('support_bot', $user->username());
        $this->assertTrue($user->supportsGuestQueries());
        $this->assertSame('/bot123456:test-token/getMe', $history[0]['request']->getUri()->getPath());
    }

    public function test_message_methods_return_typed_message_data(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                'message_id' => 10,
                'date' => 1710000000,
                'chat' => ['id' => '-1001234567890', 'type' => 'supergroup', 'title' => 'Ops'],
                'from' => ['id' => 123456789, 'is_bot' => true, 'first_name' => 'Support Bot'],
                'text' => 'Deploy finished',
            ],
        ]);

        $message = $client->sendMessageData([
            'chat_id' => '-1001234567890',
            'text' => 'Deploy finished',
        ]);

        $this->assertInstanceOf(TelegramMessageData::class, $message);
        $this->assertSame(10, $message->messageId());
        $this->assertSame('Deploy finished', $message->text());
        $this->assertSame('-1001234567890', $message->chat()?->id());
        $this->assertTrue($message->from()?->isBot());
        $this->assertSame('/bot123456:test-token/sendMessage', $history[0]['request']->getUri()->getPath());
    }

    public function test_edit_message_text_data_preserves_boolean_inline_results(): void
    {
        $history = [];
        $client = $this->client($history, [true]);

        $result = $client->editMessageTextData([
            'inline_message_id' => 'inline-id',
            'text' => 'Updated',
        ]);

        $this->assertTrue($result);
        $this->assertSame('/bot123456:test-token/editMessageText', $history[0]['request']->getUri()->getPath());
    }

    public function test_get_updates_data_returns_typed_updates(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                [
                    'update_id' => 1001,
                    'message' => [
                        'message_id' => 55,
                        'date' => 1710000000,
                        'chat' => ['id' => '123456789', 'type' => 'private'],
                        'text' => '/start',
                    ],
                ],
            ],
        ]);

        $updates = $client->getUpdatesData(['limit' => 1]);

        $this->assertContainsOnlyInstancesOf(TelegramWebhookUpdate::class, $updates);
        $this->assertSame(1001, $updates[0]->updateId());
        $this->assertSame('/start', $updates[0]->effectiveMessage()?->text());
        $this->assertSame('/bot123456:test-token/getUpdates', $history[0]['request']->getUri()->getPath());
    }

    public function test_get_file_and_webhook_info_return_typed_dto_accessors(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                'file_id' => 'file-id',
                'file_unique_id' => 'file-unique-id',
                'file_size' => 2048,
                'file_path' => 'documents/report.pdf',
            ],
            [
                'url' => 'https://example.test/telegram-bot/webhook',
                'has_custom_certificate' => false,
                'pending_update_count' => 2,
                'max_connections' => 40,
                'allowed_updates' => ['message', 'callback_query'],
            ],
        ]);

        $file = $client->getFileData(['file_id' => 'file-id']);
        $webhook = $client->getWebhookInfoData();

        $this->assertInstanceOf(TelegramFileData::class, $file);
        $this->assertSame('file-id', $file->fileId());
        $this->assertSame('documents/report.pdf', $file->filePath());
        $this->assertInstanceOf(TelegramWebhookInfoData::class, $webhook);
        $this->assertSame('https://example.test/telegram-bot/webhook', $webhook->url());
        $this->assertFalse($webhook->hasCustomCertificate());
        $this->assertSame(['message', 'callback_query'], $webhook->allowedUpdates());
    }

    public function test_chat_member_collection_results_are_typed(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                [
                    'status' => 'administrator',
                    'user' => ['id' => 123456789, 'is_bot' => false, 'first_name' => 'Alex'],
                    'can_delete_messages' => true,
                ],
            ],
        ]);

        $administrators = $client->getChatAdministratorsData(['chat_id' => '-1001234567890']);

        $this->assertContainsOnlyInstancesOf(TelegramChatMemberData::class, $administrators);
        $this->assertSame('administrator', $administrators[0]->status());
        $this->assertSame(123456789, $administrators[0]->user()?->id());
    }

    public function test_call_data_wraps_unmapped_object_results_in_generic_dto(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                'invite_link' => 'https://t.me/+invite',
                'creates_join_request' => true,
            ],
        ]);

        $invite = $client->callData('createChatInviteLink', [
            'chat_id' => '-1001234567890',
        ]);

        $this->assertInstanceOf(TelegramBotResultData::class, $invite);
        $this->assertSame('https://t.me/+invite', $invite->string('invite_link'));
        $this->assertTrue($invite->bool('creates_join_request'));
    }

    public function test_call_data_wraps_unmapped_object_lists_in_generic_dtos(): void
    {
        $history = [];
        $client = $this->client($history, [
            [
                ['name' => 'one'],
                ['name' => 'two'],
            ],
        ]);

        $results = $client->callData('someFutureObjectListMethod');

        $this->assertIsArray($results);
        $this->assertContainsOnlyInstancesOf(TelegramBotResultData::class, $results);
        $this->assertSame('one', $results[0]->string('name'));
        $this->assertSame('two', $results[1]->string('name'));
    }

    public function test_call_data_preserves_unmapped_scalar_lists(): void
    {
        $history = [];
        $client = $this->client($history, [
            ['one', 'two'],
        ]);

        $result = $client->callData('someFutureMethod');

        $this->assertSame(['one', 'two'], $result);
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     * @param  list<mixed>  $results
     */
    private function client(array &$history, array $results): TelegramBotClient
    {
        $responses = array_map(
            static fn (mixed $result): Response => new Response(200, [], json_encode(['ok' => true, 'result' => $result], JSON_THROW_ON_ERROR)),
            $results,
        );

        $handler = HandlerStack::create(new MockHandler($responses));
        $handler->push(Middleware::history($history));

        return TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: new Client([
                'handler' => $handler,
                'http_errors' => false,
            ]),
        );
    }
}
