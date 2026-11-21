<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditMessageWidgetDataRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditSharedWidgetDataRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\GetUniqueGiftRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendSharedWidgetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendWidgetRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\VerifyChatRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\VerifyUserRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TelegramBotApi104MethodsTest extends TestCase
{
    public function test_widgets_gifts_and_backdrops_use_the_official_endpoints_and_result_types(): void
    {
        $history = [];
        $chatId = '1234567890123';
        $message = ['message_id' => 7, 'chat' => ['id' => $chatId], 'widget' => ['id' => 'widget-1']];
        $responses = array_map(
            static fn (mixed $result): Response => new Response(200, [], json_encode(['ok' => true, 'result' => $result], JSON_THROW_ON_ERROR)),
            [$message, $message, true, true, true, ['name' => 'SampleGift-7'], true, ['backdrops' => []]],
        );
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));
        $client = TelegramBotClient::make(token: '123456:test-token', httpClient: new Client(['handler' => $stack]));

        $this->assertSame($message, $client->sendWidgetData(SendWidgetRequestData::make($chatId, '<p>Hello</p>', isShared: true))->toArray());
        $this->assertSame($message, $client->sendSharedWidgetData(SendSharedWidgetRequestData::make($chatId, 'widget-1'))->toArray());
        $this->assertTrue($client->answerWidgetQuery(['widget_query_id' => 'query-1', 'data' => 'received']));
        $this->assertTrue($client->editMessageWidgetData(EditMessageWidgetDataRequestData::make($chatId, 7, chatData: '')));
        $this->assertTrue($client->editSharedWidgetData(EditSharedWidgetDataRequestData::make('widget-1', '')));
        $this->assertSame('SampleGift-7', $client->getUniqueGiftData(GetUniqueGiftRequestData::make('SampleGift-7'))->name());
        $this->assertTrue($client->deleteBusinessAccountGift(['business_connection_id' => 'connection-1', 'owned_gift_id' => 'gift-1']));
        $this->assertSame([], $client->getChatShareThemeBackdropsData()->backdrops());

        $expectedRequests = [
            'sendWidget' => ['chat_id' => $chatId, 'html' => '<p>Hello</p>', 'is_shared' => true],
            'sendSharedWidget' => ['chat_id' => $chatId, 'widget_id' => 'widget-1'],
            'answerWidgetQuery' => ['widget_query_id' => 'query-1', 'data' => 'received'],
            'editMessageWidgetData' => ['chat_id' => $chatId, 'message_id' => 7, 'chat_data' => ''],
            'editSharedWidgetData' => ['widget_id' => 'widget-1', 'public_data' => ''],
            'getUniqueGift' => ['name' => 'SampleGift-7'],
            'deleteBusinessAccountGift' => ['business_connection_id' => 'connection-1', 'owned_gift_id' => 'gift-1'],
            'getChatShareThemeBackdrops' => [],
        ];

        foreach ($history as $transaction) {
            $request = $transaction['request'];
            $method = basename($request->getUri()->getPath());

            $this->assertArrayHasKey($method, $expectedRequests);
            $this->assertSame($expectedRequests[$method], json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR));
            unset($expectedRequests[$method]);
        }

        $this->assertSame([], $expectedRequests);
    }

    public function test_generic_widget_request_preserves_empty_public_data(): void
    {
        $parameters = ['widget_id' => 'widget-1', 'public_data' => ''];

        $this->assertSame($parameters, TelegramBotRequestData::forMethod('editSharedWidgetData', $parameters)->toArray());
    }

    public function test_widget_edit_requires_a_message_reference(): void
    {
        $this->assertSame(['inline_message_id' => 'inline-1', 'chat_data' => ''], EditMessageWidgetDataRequestData::make(inlineMessageId: 'inline-1', chatData: '')->toArray());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires either [inline_message_id] or both [chat_id] and [message_id]');

        EditMessageWidgetDataRequestData::make(messageId: 7);
    }

    public function test_verification_builders_accept_description_formatting_and_preserve_positional_extra(): void
    {
        $user = VerifyUserRequestData::make(1234567890123, '<a href="https://example.test">Example</a>', ['future_field' => true], customDescriptionParseMode: TelegramParseMode::HTML);
        $entities = [['type' => 'text_link', 'offset' => 0, 'length' => 7, 'url' => 'https://example.test']];
        $chat = VerifyChatRequestData::make('@example', 'Example', ['future_field' => true], customDescriptionEntities: $entities);

        $this->assertSame('HTML', $user->toArray()['custom_description_parse_mode']);
        $this->assertSame($entities, $chat->toArray()['custom_description_entities']);
        $this->assertTrue($user->toArray()['future_field']);
        $this->assertTrue($chat->toArray()['future_field']);
    }
}
