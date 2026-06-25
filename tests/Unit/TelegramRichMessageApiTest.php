<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendRichMessageDraftRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendRichMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\InputRichMessage;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichBlock;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichText;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatFullInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatJoinRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramRichMessageApiTest extends TestCase
{
    public function test_input_rich_message_serializes_for_send_rich_message(): void
    {
        $history = [];
        $client = $this->client($history);

        $client->sendRichMessage(SendRichMessageRequestData::make(
            chatId: '-1001234567890',
            richMessage: InputRichMessage::html('<h1>Deploy</h1><p>Finished</p>')
                ->skipEntityDetection(),
            directMessagesTopicId: 42,
        ));

        $payload = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot123456:test-token/sendRichMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'chat_id' => '-1001234567890',
            'rich_message' => [
                'html' => '<h1>Deploy</h1><p>Finished</p>',
                'skip_entity_detection' => true,
            ],
            'direct_messages_topic_id' => 42,
        ], $payload);
    }

    public function test_input_rich_message_serializes_for_streamed_drafts(): void
    {
        $request = SendRichMessageDraftRequestData::make(
            chatId: 123456789,
            draftId: 7,
            richMessage: InputRichMessage::markdown('<tg-thinking>Thinking...</tg-thinking>')
                ->rightToLeft(),
        );

        $this->assertSame([
            'chat_id' => 123456789,
            'draft_id' => 7,
            'rich_message' => [
                'markdown' => '<tg-thinking>Thinking...</tg-thinking>',
                'is_rtl' => true,
            ],
        ], $request->toArray());
    }

    public function test_rich_text_and_block_helpers_serialize_official_shapes(): void
    {
        $link = RichText::url(
            RichText::bold('release notes'),
            'https://example.test/releases',
        );
        $details = RichBlock::details(
            summary: 'Deploy result',
            blocks: [
                RichBlock::paragraph(['See ', $link]),
                RichBlock::pre('composer test', 'bash'),
            ],
            isOpen: true,
        );

        $this->assertSame([
            'type' => 'details',
            'summary' => 'Deploy result',
            'blocks' => [
                [
                    'type' => 'paragraph',
                    'text' => [
                        'See ',
                        [
                            'type' => 'url',
                            'text' => [
                                'type' => 'bold',
                                'text' => 'release notes',
                            ],
                            'url' => 'https://example.test/releases',
                        ],
                    ],
                ],
                [
                    'type' => 'pre',
                    'text' => 'composer test',
                    'language' => 'bash',
                ],
            ],
            'is_open' => true,
        ], $details->toArray());
    }

    public function test_rich_message_response_accessors_cover_bot_api_10_1_fields(): void
    {
        $message = TelegramMessageData::fromPayload([
            'message_id' => 10,
            'rich_message' => [
                'blocks' => [
                    ['type' => 'paragraph', 'text' => 'Hello'],
                ],
                'is_rtl' => false,
            ],
        ]);
        $user = TelegramUserData::fromPayload([
            'id' => 123,
            'supports_join_request_queries' => true,
        ]);
        $chat = TelegramChatFullInfoData::fromPayload([
            'id' => -100,
            'guard_bot' => [
                'id' => 456,
                'is_bot' => true,
                'first_name' => 'Guard',
            ],
        ]);
        $joinRequest = TelegramChatJoinRequestData::fromPayload([
            'chat' => ['id' => -100],
            'from' => ['id' => 123],
            'date' => 1710000000,
            'query_id' => 'join-query-id',
        ]);

        $this->assertSame('paragraph', $message->richMessageData()?->blockData()[0]->toArray()['type'] ?? null);
        $this->assertFalse($message->richMessageData()?->isRtl());
        $this->assertTrue($user->supportsJoinRequestQueries());
        $this->assertSame(456, $chat->guardBot()?->id());
        $this->assertSame('join-query-id', $joinRequest->queryId());
    }

    public function test_input_rich_message_requires_exactly_one_non_empty_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('field [html] must not be empty');

        InputRichMessage::html('');
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function client(array &$history): TelegramBotClient
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ]));
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
