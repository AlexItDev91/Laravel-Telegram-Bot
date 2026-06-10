<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TelegramMessageBuilderTest extends TestCase
{
    public function test_builds_text_message_payloads(): void
    {
        $message = TelegramMessage::text('Deploy finished')
            ->to('-1001234567890', messageThreadId: '42')
            ->parseMode(TelegramParseMode::HTML)
            ->silent()
            ->protectContent()
            ->replyMarkup(InlineKeyboardMarkup::singleButton(
                InlineKeyboardButton::callback('Retry', 'deploy:retry'),
            ))
            ->parameter('disable_web_page_preview', true);

        $this->assertSame(TelegramBotApiMethod::sendMessage, $message->method());
        $this->assertSame('sendMessage', $message->methodName());
        $this->assertTrue($message->hasChatId());
        $this->assertSame([
            'text' => 'Deploy finished',
            'chat_id' => '-1001234567890',
            'message_thread_id' => '42',
            'parse_mode' => 'HTML',
            'disable_notification' => true,
            'protect_content' => true,
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Retry',
                            'callback_data' => 'deploy:retry',
                        ],
                    ],
                ],
            ],
            'disable_web_page_preview' => true,
        ], $message->payload());
    }

    public function test_builds_photo_and_document_payloads(): void
    {
        $photo = TelegramMessage::photo('photo-file-id')
            ->caption('Daily report')
            ->parseMode(TelegramParseMode::MarkdownV2)
            ->spoiler()
            ->showCaptionAboveMedia();

        $document = TelegramMessage::document('document-file-id')
            ->caption('Invoice')
            ->disableContentTypeDetection()
            ->allowPaidBroadcast();

        $this->assertSame(TelegramBotApiMethod::sendPhoto, $photo->method());
        $this->assertSame('photo-file-id', $photo->payload()['photo']);
        $this->assertSame('Daily report', $photo->payload()['caption']);
        $this->assertSame('MarkdownV2', $photo->payload()['parse_mode']);
        $this->assertTrue($photo->payload()['has_spoiler']);
        $this->assertTrue($photo->payload()['show_caption_above_media']);

        $this->assertSame(TelegramBotApiMethod::sendDocument, $document->method());
        $this->assertSame('document-file-id', $document->payload()['document']);
        $this->assertSame('Invoice', $document->payload()['caption']);
        $this->assertTrue($document->payload()['disable_content_type_detection']);
        $this->assertTrue($document->payload()['allow_paid_broadcast']);
    }

    public function test_rejects_empty_required_values(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('field [text] must not be empty');

        TelegramMessage::text('   ');
    }
}
