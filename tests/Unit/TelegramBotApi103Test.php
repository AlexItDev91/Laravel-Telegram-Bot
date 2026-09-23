<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\ReplyParameters;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\EphemeralMessageParameters;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\ReplyKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\EditEphemeralMessageMediaRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotCommandData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatFullInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\InputRichMessage;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\InputRichBlock;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\InputRichMessageMedia;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\InputMediaVoiceNote;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichMessageButton;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichBlock;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichText;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramUpdateType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotSubscriptionState;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramRichButtonStyle;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramRichBlockType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramRichTextType;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodRegistry;
use AlexItDev91\LaravelTelegramBot\InputFile;
use PHPUnit\Framework\TestCase;

class TelegramBotApi103Test extends TestCase
{
    public function test_official_methods_and_updates_are_registered(): void
    {
        $this->assertSame('10.3', TelegramBotApiMethodRegistry::BOT_API_VERSION);
        $this->assertSame('2026-08-24', TelegramBotApiMethodRegistry::BOT_API_RELEASE_DATE);
        $this->assertCount(185, TelegramBotApiMethod::cases());
        $this->assertCount(27, TelegramUpdateType::cases());
        $this->assertSame('subscription', TelegramWebhookUpdate::fromPayload([
            'update_id' => 1,
            'subscription' => ['invoice_payload' => 'order-1', 'state' => 'active', 'user' => ['id' => 123]],
        ])->type());
        $this->assertSame('stopped_message_generation', TelegramWebhookUpdate::fromPayload([
            'update_id' => 2,
            'stopped_message_generation' => ['draft_id' => 7, 'chat' => ['id' => 123]],
        ])->type());
    }

    public function test_rich_message_supports_blocks_and_media_as_objects(): void
    {
        $message = InputRichMessage::blocks(InputRichBlock::paragraph('Hello'));

        $this->assertSame([
            'blocks' => [['type' => 'paragraph', 'text' => 'Hello']],
        ], $message->toArray());

        $mediaMessage = InputRichMessage::html('<p><a href="tg://audio?id=voice">Voice</a></p>')
            ->withMedia(InputRichMessageMedia::make('voice', InputMediaVoiceNote::fromFileId('voice-file-id')));

        $this->assertSame([
            'html' => '<p><a href="tg://audio?id=voice">Voice</a></p>',
            'media' => [[
                'id' => 'voice',
                'media' => ['type' => 'voice_note', 'media' => 'voice-file-id'],
            ]],
        ], $mediaMessage->toArray());
    }

    public function test_ephemeral_reply_does_not_require_a_regular_message_id(): void
    {
        $reply = ReplyParameters::forEphemeralMessage(42);

        $this->assertSame(['ephemeral_message_id' => 42], $reply->toArray());
    }

    public function test_ephemeral_parameters_and_new_update_payloads_have_typed_accessors(): void
    {
        $this->assertSame([
            'receiver_user_id' => '1234567890123',
            'callback_query_id' => 'callback-1',
            'replace_callback_query_message' => true,
        ], EphemeralMessageParameters::forUser('1234567890123')
            ->fromCallbackQuery('callback-1')
            ->replaceCallbackQueryMessage()
            ->toArray());

        $subscription = TelegramWebhookUpdate::fromPayload([
            'update_id' => 1,
            'subscription' => ['user' => ['id' => '1234567890123'], 'invoice_payload' => 'order-1', 'state' => 'failed'],
        ])->subscription();
        $this->assertSame('1234567890123', $subscription?->user()?->id());
        $this->assertSame('order-1', $subscription?->invoicePayload());
        $this->assertSame(TelegramBotSubscriptionState::Failed, $subscription?->stateEnum());

        $stopped = TelegramWebhookUpdate::fromPayload([
            'update_id' => 2,
            'stopped_message_generation' => ['draft_id' => 7, 'chat' => ['id' => '-1001234567890']],
        ])->stoppedMessageGeneration();
        $this->assertSame(7, $stopped?->draftId());
        $this->assertSame('-1001234567890', $stopped?->chat()?->id());
    }

    public function test_new_message_and_community_fields_have_typed_accessors(): void
    {
        $message = TelegramMessageData::fromPayload([
            'receiver_user' => ['id' => '1234567890123'],
            'ephemeral_message_id' => 55,
            'community_chat_joined' => ['community' => ['id' => 555, 'name' => 'Team']],
        ]);
        $this->assertSame('1234567890123', $message->receiverUser()?->id());
        $this->assertSame(55, $message->ephemeralMessageId());
        $this->assertSame('Team', $message->communityChatJoined()?->community()?->name());

        $chat = TelegramChatFullInfoData::fromPayload(['community' => ['id' => 555, 'name' => 'Team']]);
        $this->assertSame(555, $chat->community()?->id());
        $this->assertTrue(TelegramBotCommandData::fromPayload(['is_ephemeral' => true])->isEphemeral());
    }

    public function test_new_rich_button_and_block_shapes_are_typed(): void
    {
        $button = RichMessageButton::callback('Retry', 'retry:1')
            ->withStyle(TelegramRichButtonStyle::Primary);

        $this->assertSame([
            'text' => 'Retry',
            'style' => 'primary',
            'callback_data' => 'retry:1',
        ], $button->toArray());
        $this->assertSame([
            'type' => 'button',
            'button' => $button->toArray(),
        ], RichText::button($button)->toArray());
        $this->assertSame([
            'type' => 'buttons',
            'buttons' => [$button->toArray()],
        ], InputRichBlock::buttons($button)->toArray());
        $this->assertSame([
            'type' => 'expandable_blockquote',
            'text' => 'More',
        ], InputRichBlock::expandableBlockquote('More')->toArray());
    }

    public function test_existing_positional_request_arguments_keep_their_meaning(): void
    {
        $request = SendMessageRequestData::make(123, 'Hello', null, null, null, TelegramParseMode::HTML);

        $this->assertSame('HTML', $request->toArray()['parse_mode']);
    }

    public function test_update_types_and_rich_discriminators_use_enums(): void
    {
        $this->assertSame(
            array_map(static fn (TelegramUpdateType $type): string => $type->value, TelegramUpdateType::cases()),
            TelegramWebhookUpdate::updateTypes(),
        );
        $this->assertSame(TelegramRichBlockType::Paragraph, RichBlock::paragraph('Text')->typeEnum());
        $this->assertSame(TelegramRichTextType::Bold, RichText::bold('Text')->typeEnum());
    }

    public function test_new_keyboard_and_gift_fields_have_typed_objects(): void
    {
        $this->assertSame(['text' => 'Unavailable', 'disabled' => []], InlineKeyboardButton::disabled('Unavailable')->toArray());
        $this->assertSame([
            'inline_keyboard' => [[['text' => 'Unavailable', 'disabled' => []]]],
            'force_reply' => true,
        ], InlineKeyboardMarkup::singleButton(InlineKeyboardButton::disabled('Unavailable'))->forceReply()->toArray());
        $this->assertSame([
            'keyboard' => [['Continue']],
            'force_reply' => true,
        ], ReplyKeyboardMarkup::make()->row('Continue')->forceReply()->toArray());
        $this->assertSame([
            'keyboard' => [['Continue']],
            'is_persistent' => true,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'input_field_placeholder' => 'Choose',
            'selective' => true,
        ], ReplyKeyboardMarkup::make()->row('Continue')->persistent()->resize()->oneTime(false)->placeholder('Choose')->selective()->toArray());

        $gift = TelegramMessageData::fromPayload([
            'unique_gift' => ['text' => 'Thanks', 'entities' => [['type' => 'bold', 'offset' => 0, 'length' => 6]], 'is_private' => true],
        ])->uniqueGiftInfo();
        $this->assertSame('Thanks', $gift?->text());
        $this->assertSame('bold', $gift?->entities()[0]->type());
        $this->assertTrue($gift?->isPrivate());
    }

    public function test_ephemeral_media_edit_attaches_a_nested_file(): void
    {
        $request = EditEphemeralMessageMediaRequestData::make(
            chatId: -1001234567890,
            receiverUserId: 1234567890123,
            ephemeralMessageId: 42,
            media: InputMediaVoiceNote::fromInputFile(InputFile::fromContents('voice', 'note.ogg')),
        );

        $this->assertTrue($request->containsFiles());
        $parts = $request->multipart();
        $media = array_find($parts, static fn (array $part): bool => $part['name'] === 'media');
        $this->assertIsArray($media);
        $this->assertSame('attach://file_0', json_decode((string) $media['contents'], true, flags: JSON_THROW_ON_ERROR)['media']);
        $this->assertCount(5, $parts);
    }
}
