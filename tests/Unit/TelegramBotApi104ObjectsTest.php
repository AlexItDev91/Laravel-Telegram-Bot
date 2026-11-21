<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\InputSharedWidgetMessageContent;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InputWidgetMessageContent;
use AlexItDev91\LaravelTelegramBot\DTO\Requests\AnswerInlineQueryRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\Rich\RichText;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatFullInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatShareThemeBackdropsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageEntityData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramMessageEntityType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramWidgetQueryType;
use AlexItDev91\LaravelTelegramBot\Support\TelegramUpdateChatDiscovery;
use PHPUnit\Framework\TestCase;

final class TelegramBotApi104ObjectsTest extends TestCase
{
    public function test_widget_query_exposes_the_sender_and_chat_without_constructing_a_message(): void
    {
        $payload = [
            'update_id' => 42,
            'widget_query' => [
                'id' => 'widget-query',
                'from' => ['id' => 4500000000000],
                'chat' => ['id' => 4500000000000, 'type' => 'private'],
                'message_id' => 17,
                'chat_instance' => 'chat-instance',
                'data' => 'refresh',
                'type' => ['type' => 'data', 'wallet_card' => ['amount' => '18446744073709551615']],
                'widget_id' => 'shared',
            ],
        ];
        $update = TelegramWebhookUpdate::fromPayload($payload);
        $query = $update->widgetQuery();

        $this->assertSame('widget_query', $update->type());
        $this->assertSame($payload['widget_query'], $update->data());
        $this->assertSame(4500000000000, $update->effectiveUser()?->id());
        $this->assertSame(4500000000000, $update->effectiveChat()?->id());
        $this->assertNull($update->effectiveMessage());
        $this->assertSame('widget-query', $query?->id());
        $this->assertSame(17, $query?->messageId());
        $this->assertSame('refresh', $query?->data());
        $this->assertSame('shared', $query?->widgetId());
        $this->assertSame(TelegramWidgetQueryType::Data, $query?->type()?->typeEnum());
        $this->assertSame('18446744073709551615', $query?->type()?->walletCard()?->amount());
        $this->assertSame($payload, $update->toArray());

        $rows = (new TelegramUpdateChatDiscovery())->rows([$payload]);
        $this->assertCount(1, $rows);
        $this->assertSame('4500000000000', $rows[0]['chat_id']);
        $this->assertSame('17', $rows[0]['message_id']);
    }

    public function test_widget_query_variants_and_future_types_keep_their_data(): void
    {
        $wallet = ['address' => 'wallet-address', 'amount' => '100', 'public_key' => '00', 'state_init' => 'AA=='];
        $query = TelegramWebhookUpdate::fromPayload([
            'update_id' => 43,
            'shared_widget_query' => [
                'id' => 'shared-query',
                'from' => ['id' => 11],
                'type' => ['type' => 'sign_data', 'wallet' => $wallet, 'sign_data' => '{"text":"signed"}'],
                'data' => 'submit',
                'widget_id' => 'shared',
            ],
        ]);

        $this->assertSame('shared_widget_query', $query->type());
        $this->assertSame(11, $query->effectiveUser()?->id());
        $this->assertNull($query->effectiveChat());
        $this->assertSame(TelegramWidgetQueryType::SignData, $query->sharedWidgetQuery()?->type()?->typeEnum());
        $this->assertSame('{"text":"signed"}', $query->sharedWidgetQuery()?->type()?->signData());
        $this->assertSame($wallet, $query->sharedWidgetQuery()?->type()?->wallet()?->toArray());

        $inline = TelegramWebhookUpdate::fromPayload([
            'widget_query' => [
                'inline_message_id' => 'inline-widget',
                'chat_type' => 'sender',
                'type' => ['type' => 'sent_transaction', 'msg_hash' => 'message-hash', 'wallet' => $wallet],
            ],
        ])->widgetQuery();

        $this->assertNull($inline?->chat());
        $this->assertSame('inline-widget', $inline?->inlineMessageId());
        $this->assertSame('sender', $inline?->chatType());
        $this->assertSame(TelegramWidgetQueryType::SentTransaction, $inline?->type()?->typeEnum());
        $this->assertSame('message-hash', $inline?->type()?->msgHash());

        $future = ['type' => 'future_type', 'detail' => 'preserved'];
        $type = TelegramWebhookUpdate::fromPayload(['widget_query' => ['type' => $future]])->widgetQuery()?->type();
        $this->assertNull($type?->typeEnum());
        $this->assertSame($future, $type?->toArray());
    }

    public function test_message_widgets_rich_quotes_and_service_messages_are_readable(): void
    {
        $richTexts = ['plain', ['type' => 'bold', 'text' => 'bold'], ['nested', ['type' => 'italic', 'text' => 'italic']]];
        $widget = ['html' => '<p>Widget</p>', 'public_data' => '', 'id' => 'shared'];
        $message = TelegramMessageData::fromPayload([
            'widget' => $widget,
            'external_reply' => ['widget' => $widget],
            'quote' => ['text' => '', 'position' => 6, 'rich_texts' => $richTexts, 'is_manual' => true],
            'ton_wallet_transfer' => ['transaction_id' => '', 'amount' => '-18446744073709551615'],
            'paid_messages_waived' => [],
            'entities' => [
                ['type' => 'ton_address', 'offset' => 0, 'length' => 6],
                ['type' => 'bank_card_number', 'offset' => 7, 'length' => 16],
            ],
        ]);

        $this->assertSame($widget, $message->widget()?->toArray());
        $this->assertSame('<p>Widget</p>', $message->externalReply()?->widget()?->html());
        $this->assertSame('', $message->quote()?->text());
        $this->assertSame($richTexts, $message->quote()?->richTexts());
        $this->assertSame(6, $message->quote()?->position());
        $this->assertTrue($message->quote()?->isManual());
        $this->assertSame('', $message->tonWalletTransfer()?->transactionId());
        $this->assertSame('-18446744073709551615', $message->tonWalletTransfer()?->amount());
        $this->assertSame([], $message->paidMessagesWaived()?->toArray());
        $this->assertSame([TelegramMessageEntityType::TonAddress, TelegramMessageEntityType::BankCardNumber], array_map(
            static fn (TelegramMessageEntityData $entity): ?TelegramMessageEntityType => $entity->typeEnum(),
            $message->entitiesData(),
        ));
        $this->assertSame(
            ['type' => 'ton_address', 'text' => 'Wallet', 'address' => 'wallet-address'],
            RichText::tonAddress('Wallet', 'wallet-address')->toArray(),
        );
    }

    public function test_chat_info_exposes_share_theme_and_nested_ton_wallet_card(): void
    {
        $chat = TelegramChatFullInfoData::fromPayload([
            'share_theme' => [
                'type' => 'emoji',
                'emojis' => [['type' => 'emoji', 'emoji' => '👍'], ['type' => 'custom_emoji', 'custom_emoji_id' => '123']],
                'background_backdrop_id' => 42,
            ],
            'ton_wallet_card' => [
                'address' => 'wallet-address',
                'stickers' => [[
                    'position' => ['x_percentage' => 50, 'y_percentage' => 42.5],
                    'nft' => [
                        'index' => '18446744073709551615',
                        'attributes' => [['trait_type' => 'color', 'value' => 'red']],
                        'gift' => ['name' => 'Gift-1'],
                    ],
                ]],
            ],
        ]);

        $theme = $chat->shareTheme();
        $this->assertSame('emoji', $theme?->type());
        $this->assertSame('👍', $theme?->emojis()[0]->emoji());
        $this->assertSame('123', $theme?->emojis()[1]->customEmojiId());
        $this->assertSame(42, $theme?->backgroundBackdropId());
        $this->assertSame('wallet-address', $chat->tonWalletCard()?->address());

        $sticker = $chat->tonWalletCard()?->stickers()[0];
        $this->assertSame(50.0, $sticker?->position()?->xPercentage());
        $this->assertSame(42.5, $sticker?->position()?->yPercentage());
        $this->assertSame('18446744073709551615', $sticker?->nft()?->index());
        $this->assertSame('color', $sticker?->nft()?->attributes()[0]->traitType());
        $this->assertSame('Gift-1', $sticker?->nft()?->gift()?->name());
    }

    public function test_share_theme_backdrop_hydrates_colors(): void
    {
        $backdrops = TelegramChatShareThemeBackdropsData::fromPayload([
            'backdrops' => [[
                'id' => 7,
                'name' => 'Purple',
                'colors' => ['center_color' => 0x663399, 'edge_color' => 0x6633FF, 'symbol_color' => 0, 'text_color' => 0xFFFFFF],
            ]],
        ])->backdrops();

        $this->assertCount(1, $backdrops);
        $this->assertSame(7, $backdrops[0]->id());
        $this->assertSame('Purple', $backdrops[0]->name());
        $this->assertSame(0x663399, $backdrops[0]->colors()?->centerColor());
        $this->assertSame(0, $backdrops[0]->colors()?->symbolColor());
    }

    public function test_inline_widget_content_serializes_inside_inline_query_results(): void
    {
        $request = AnswerInlineQueryRequestData::make('query', [
            ['type' => 'article', 'id' => 'new', 'title' => 'Widget', 'input_message_content' => new InputWidgetMessageContent(
                html: '<p>Hello</p>',
                publicData: '',
                parseMode: TelegramParseMode::HTML,
                allowQueries: true,
                isShared: false,
            )],
            ['type' => 'article', 'id' => 'existing', 'title' => 'Shared widget', 'input_message_content' => new InputSharedWidgetMessageContent(
                widgetId: 'existing-widget',
                chatData: '',
                showCaptionAboveMedia: false,
            )],
        ]);

        $results = $request->toArray()['results'];
        $this->assertSame([
            'html' => '<p>Hello</p>',
            'public_data' => '',
            'parse_mode' => 'HTML',
            'allow_queries' => true,
            'is_shared' => false,
        ], $results[0]['input_message_content']);
        $this->assertSame([
            'widget_id' => 'existing-widget',
            'chat_data' => '',
            'show_caption_above_media' => false,
        ], $results[1]['input_message_content']);
    }
}
