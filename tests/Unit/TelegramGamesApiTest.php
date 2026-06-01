<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Games\CallbackGame;
use AlexItDev91\LaravelTelegramBot\DTO\Games\GetGameHighScoresData;
use AlexItDev91\LaravelTelegramBot\DTO\Games\InlineQueryResultGame;
use AlexItDev91\LaravelTelegramBot\DTO\Games\SendGameData;
use AlexItDev91\LaravelTelegramBot\DTO\Games\SetGameScoreData;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramGamesApiTest extends TestCase
{
    public function test_send_game_data_calls_game_endpoint_with_callback_game_markup(): void
    {
        $history = [];
        $client = $this->client($history);

        $client->sendGame(new SendGameData(
            chatId: '@target_bot',
            gameShortName: 'space_race',
            businessConnectionId: 'business-1',
            messageThreadId: 10,
            allowPaidBroadcast: true,
            replyMarkup: [
                'inline_keyboard' => [
                    [
                        ['text' => 'Play', 'callback_game' => new CallbackGame()],
                        ['text' => 'Scores', 'url' => 'https://example.test/scores'],
                    ],
                ],
            ],
        ));

        $payload = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot123456:test-token/sendGame', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'business_connection_id' => 'business-1',
            'chat_id' => '@target_bot',
            'message_thread_id' => 10,
            'game_short_name' => 'space_race',
            'allow_paid_broadcast' => true,
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        ['text' => 'Play', 'callback_game' => []],
                        ['text' => 'Scores', 'url' => 'https://example.test/scores'],
                    ],
                ],
            ],
        ], $payload);
    }

    public function test_game_score_and_inline_game_data_serializes_official_parameters(): void
    {
        $setScore = new SetGameScoreData(
            userId: '9007199254740991',
            score: 1200,
            force: true,
            disableEditMessage: true,
            chatId: '-1001234567890',
            messageId: 55,
        );
        $highScores = new GetGameHighScoresData(
            userId: '9007199254740991',
            inlineMessageId: 'inline-message',
        );
        $inlineResult = new InlineQueryResultGame(
            id: 'result-1',
            gameShortName: 'space_race',
            replyMarkup: [
                'inline_keyboard' => [
                    [['text' => 'Play', 'callback_game' => new CallbackGame()]],
                ],
            ],
        );

        $this->assertSame([
            'user_id' => '9007199254740991',
            'score' => 1200,
            'force' => true,
            'disable_edit_message' => true,
            'chat_id' => '-1001234567890',
            'message_id' => 55,
        ], $setScore->json());

        $this->assertSame([
            'user_id' => '9007199254740991',
            'inline_message_id' => 'inline-message',
        ], $highScores->json());

        $this->assertSame([
            'type' => 'game',
            'id' => 'result-1',
            'game_short_name' => 'space_race',
            'reply_markup' => [
                'inline_keyboard' => [
                    [['text' => 'Play', 'callback_game' => []]],
                ],
            ],
        ], $inlineResult->toArray());
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function client(array &$history): TelegramBotClient
    {
        return TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient($history),
        );
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function fakeHttpClient(array &$history): Client
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));

        return new Client([
            'handler' => $handler,
            'http_errors' => false,
        ]);
    }
}
