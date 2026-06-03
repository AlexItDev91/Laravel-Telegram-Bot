<?php

return [
    'default' => env('TELEGRAM_BOT', 'default'),

    'token' => env('TELEGRAM_BOT_TOKEN'),

    'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),

    'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),

    'logging' => [
        'enabled' => env('TELEGRAM_BOT_LOGGING_ENABLED', true),
    ],

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
        //     'direct_messages_topic_id' => env('TELEGRAM_INBOX_DIRECT_MESSAGES_TOPIC_ID'),
        // ],
    ],

    'webhook' => [
        'bot' => env('TELEGRAM_WEBHOOK_BOT', env('TELEGRAM_BOT', 'default')),

        'bot_username' => env('TELEGRAM_WEBHOOK_BOT_USERNAME'),

        'secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN'),

        'require_secret' => env('TELEGRAM_WEBHOOK_REQUIRE_SECRET', env('APP_ENV') === 'production'),

        'handler' => null,

        'handlers' => [
            // 'message' => App\Telegram\Handlers\MessageHandler::class,
            // 'callback_query' => App\Telegram\Handlers\CallbackQueryHandler::class,
        ],

        'commands' => [
            // 'start' => App\Telegram\Commands\StartCommand::class,
        ],

        'fallback_handler' => null,

        'dispatch_event' => true,

        'route' => [
            'enabled' => env('TELEGRAM_WEBHOOK_ROUTE_ENABLED', true),
            'uri' => env('TELEGRAM_WEBHOOK_ROUTE_URI', 'telegram-bot/webhook'),
            'name' => env('TELEGRAM_WEBHOOK_ROUTE_NAME', 'telegram-bot.webhook'),
            'middleware' => [],
        ],
    ],
];
