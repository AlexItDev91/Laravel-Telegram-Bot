<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\Support\TelegramUpdateChatDiscovery;
use PHPUnit\Framework\TestCase;

class TelegramUpdateChatDiscoveryTest extends TestCase
{
    public function test_extracts_copy_ready_chat_and_thread_ids_from_updates(): void
    {
        $discovery = new TelegramUpdateChatDiscovery();
        $rows = $discovery->rows([
            [
                'update_id' => 1001,
                'message' => [
                    'message_id' => 10,
                    'message_thread_id' => 42,
                    'chat' => [
                        'id' => -1009007199254740991,
                        'type' => 'supergroup',
                        'title' => 'Operations',
                    ],
                ],
            ],
            [
                'update_id' => 1002,
                'channel_post' => [
                    'message_id' => 11,
                    'chat' => [
                        'id' => -1001234567890,
                        'type' => 'channel',
                        'title' => 'Alerts',
                    ],
                ],
            ],
            [
                'update_id' => 1003,
                'callback_query' => [
                    'message' => [
                        'message_id' => 12,
                        'chat' => [
                            'id' => -1005555555555,
                            'type' => 'group',
                            'title' => 'Support',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertSame([
            [
                'update_id' => '1001',
                'update_type' => 'message',
                'source' => 'message',
                'chat_id' => '-1009007199254740991',
                'message_thread_id' => '42',
                'direct_messages_topic_id' => '',
                'message_id' => '10',
                'chat_type' => 'supergroup',
                'chat_title' => 'Operations',
            ],
            [
                'update_id' => '1002',
                'update_type' => 'channel_post',
                'source' => 'channel_post',
                'chat_id' => '-1001234567890',
                'message_thread_id' => '',
                'direct_messages_topic_id' => '',
                'message_id' => '11',
                'chat_type' => 'channel',
                'chat_title' => 'Alerts',
            ],
            [
                'update_id' => '1003',
                'update_type' => 'callback_query',
                'source' => 'callback_query.message',
                'chat_id' => '-1005555555555',
                'message_thread_id' => '',
                'direct_messages_topic_id' => '',
                'message_id' => '12',
                'chat_type' => 'group',
                'chat_title' => 'Support',
            ],
        ], $rows);

        $this->assertSame([
            'TELEGRAM_CHAT_ID=-1009007199254740991',
            'TELEGRAM_MESSAGE_THREAD_ID=42',
            'TELEGRAM_CHAT_ID_2=-1001234567890',
            'TELEGRAM_CHAT_ID_3=-1005555555555',
        ], $discovery->envLines($rows));
    }
}
