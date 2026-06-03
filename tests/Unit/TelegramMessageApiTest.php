<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\AnswerCallbackQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\EditMessageTextData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\LinkPreviewOptions;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\ReplyParameters;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendDocumentData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendPhotoData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SuggestedPostParameters;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SuggestedPostPrice;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramMessageApiTest extends TestCase
{
    public function test_send_message_data_calls_telegram_send_message_endpoint(): void
    {
        $history = [];
        $client = $this->client($history);

        $client->sendMessage(new SendMessageData(
            chatId: '-1001234567890',
            text: 'Deploy finished',
            messageThreadId: '42',
            parseMode: TelegramParseMode::HTML,
            linkPreviewOptions: ['is_disabled' => true],
            replyMarkup: [
                'inline_keyboard' => [
                    [['text' => 'Open', 'url' => 'https://example.test']],
                ],
            ],
        ));

        $payload = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot123456:test-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'chat_id' => '-1001234567890',
            'message_thread_id' => '42',
            'text' => 'Deploy finished',
            'parse_mode' => 'HTML',
            'link_preview_options' => ['is_disabled' => true],
            'reply_markup' => [
                'inline_keyboard' => [
                    [['text' => 'Open', 'url' => 'https://example.test']],
                ],
            ],
        ], $payload);
    }

    public function test_send_message_data_accepts_typed_nested_input_objects(): void
    {
        $data = new SendMessageData(
            chatId: '123456789',
            text: 'Build failed',
            linkPreviewOptions: LinkPreviewOptions::disabled(),
            suggestedPostParameters: new SuggestedPostParameters(
                price: SuggestedPostPrice::stars(25),
                sendDate: 1710000300,
            ),
            replyParameters: new ReplyParameters(
                messageId: 10,
                quote: 'Earlier message',
                quoteParseMode: TelegramParseMode::HTML,
            ),
            replyMarkup: InlineKeyboardMarkup::singleButton(
                InlineKeyboardButton::callback('Retry', 'deploy:retry'),
            ),
        );

        $this->assertSame([
            'chat_id' => '123456789',
            'text' => 'Build failed',
            'link_preview_options' => ['is_disabled' => true],
            'suggested_post_parameters' => [
                'price' => ['currency' => 'XTR', 'amount' => 25],
                'send_date' => 1710000300,
            ],
            'reply_parameters' => [
                'message_id' => 10,
                'quote' => 'Earlier message',
                'quote_parse_mode' => 'HTML',
            ],
            'reply_markup' => [
                'inline_keyboard' => [
                    [['text' => 'Retry', 'callback_data' => 'deploy:retry']],
                ],
            ],
        ], $data->json());
    }

    public function test_edit_message_text_data_supports_inline_and_chat_message_references(): void
    {
        $inline = new EditMessageTextData(
            text: 'Updated inline text',
            inlineMessageId: 'inline-message-id',
            replyMarkup: ['inline_keyboard' => []],
        );
        $chat = new EditMessageTextData(
            text: 'Updated chat text',
            chatId: '-1001234567890',
            messageId: 55,
        );

        $this->assertSame([
            'inline_message_id' => 'inline-message-id',
            'text' => 'Updated inline text',
            'reply_markup' => ['inline_keyboard' => []],
        ], $inline->json());
        $this->assertSame([
            'chat_id' => '-1001234567890',
            'message_id' => 55,
            'text' => 'Updated chat text',
        ], $chat->json());
    }

    public function test_send_photo_and_document_data_support_multipart_uploads(): void
    {
        $history = [];
        $photoPath = tempnam(sys_get_temp_dir(), 'telegram-photo-');
        $documentPath = tempnam(sys_get_temp_dir(), 'telegram-document-');
        file_put_contents($photoPath, 'photo-content');
        file_put_contents($documentPath, 'document-content');

        try {
            $client = $this->client($history, 2);

            $client->sendPhoto(new SendPhotoData(
                chatId: '-1001234567890',
                photo: InputFile::fromPath($photoPath, 'photo.jpg', 'image/jpeg'),
                caption: 'Photo caption',
                hasSpoiler: true,
            ));
            $client->sendDocument(new SendDocumentData(
                chatId: '-1001234567890',
                document: InputFile::fromPath($documentPath, 'report.pdf', 'application/pdf'),
                caption: 'Report',
                disableContentTypeDetection: true,
            ));

            $photoBody = (string) $history[0]['request']->getBody();
            $documentBody = (string) $history[1]['request']->getBody();

            $this->assertSame('/bot123456:test-token/sendPhoto', $history[0]['request']->getUri()->getPath());
            $this->assertStringStartsWith('multipart/form-data;', $history[0]['request']->getHeaderLine('Content-Type'));
            $this->assertStringContainsString('name="photo"', $photoBody);
            $this->assertStringContainsString('filename="photo.jpg"', $photoBody);
            $this->assertStringContainsString('name="has_spoiler"', $photoBody);
            $this->assertStringContainsString('true', $photoBody);

            $this->assertSame('/bot123456:test-token/sendDocument', $history[1]['request']->getUri()->getPath());
            $this->assertStringStartsWith('multipart/form-data;', $history[1]['request']->getHeaderLine('Content-Type'));
            $this->assertStringContainsString('name="document"', $documentBody);
            $this->assertStringContainsString('filename="report.pdf"', $documentBody);
            $this->assertStringContainsString('name="disable_content_type_detection"', $documentBody);
            $this->assertStringContainsString('true', $documentBody);
        } finally {
            unlink($photoPath);
            unlink($documentPath);
        }
    }

    public function test_answer_callback_query_data_serializes_official_parameters(): void
    {
        $payload = (new AnswerCallbackQueryData(
            callbackQueryId: 'callback-id',
            text: 'Done',
            showAlert: true,
            url: 'https://example.test',
            cacheTime: 30,
        ))->json();

        $this->assertSame([
            'callback_query_id' => 'callback-id',
            'text' => 'Done',
            'show_alert' => true,
            'url' => 'https://example.test',
            'cache_time' => 30,
        ], $payload);
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function client(array &$history, int $responses = 1): TelegramBotClient
    {
        return TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient($history, $responses),
        );
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function fakeHttpClient(array &$history, int $responses): Client
    {
        $handler = HandlerStack::create(new MockHandler(array_fill(
            0,
            $responses,
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        )));
        $handler->push(Middleware::history($history));

        return new Client([
            'handler' => $handler,
            'http_errors' => false,
        ]);
    }
}
