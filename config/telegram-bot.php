<?php

return [
    'default' => env('TELEGRAM_BOT', 'default'),

    'token' => env('TELEGRAM_BOT_TOKEN'),

    'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),

    'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),

    'bots' => [
        'default' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),
            'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),
        ],
    ],

    'channels' => [
        // 'inbox' => [
        //     'bot' => 'default',
        //     'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
        //     'message_thread_id' => env('TELEGRAM_INBOX_MESSAGE_THREAD_ID'),
        // ],
    ],
];
