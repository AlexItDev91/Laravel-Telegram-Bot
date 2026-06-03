<?php

return [
    'default' => env('TELEGRAM_BOT', 'default'),

    'token' => env('TELEGRAM_BOT_TOKEN'),

    'api_url' => env('TELEGRAM_BOT_API_URL', 'https://api.telegram.org'),

    'timeout' => env('TELEGRAM_BOT_TIMEOUT', 10),

    'logging' => [
        'enabled' => env('TELEGRAM_BOT_LOGGING_ENABLED', true),
    ],

    'retry' => [
        'enabled' => env('TELEGRAM_BOT_RETRY_ENABLED', false),
        'max_attempts' => env('TELEGRAM_BOT_RETRY_MAX_ATTEMPTS', 2),
        'retry_transport_failures' => true,
        'status_codes' => [429, 500, 502, 503, 504],
        'base_delay_seconds' => env('TELEGRAM_BOT_RETRY_BASE_DELAY_SECONDS', 0.25),
        'sleep' => true,
    ],

    'rate_limit' => [
        'enabled' => env('TELEGRAM_BOT_RATE_LIMIT_ENABLED', false),
        'store' => env('TELEGRAM_BOT_RATE_LIMIT_STORE'),
        'max_attempts' => env('TELEGRAM_BOT_RATE_LIMIT_MAX_ATTEMPTS', 30),
        'decay_seconds' => env('TELEGRAM_BOT_RATE_LIMIT_DECAY_SECONDS', 1),
        'key_prefix' => env('TELEGRAM_BOT_RATE_LIMIT_KEY_PREFIX', 'telegram-bot:rate-limit'),
    ],

    'observability' => [
        'enabled' => env('TELEGRAM_BOT_OBSERVABILITY_ENABLED', false),
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

    'conversation' => [
        'enabled' => env('TELEGRAM_CONVERSATION_ENABLED', false),
        'store' => env('TELEGRAM_CONVERSATION_STORE'),
        'ttl' => env('TELEGRAM_CONVERSATION_TTL', 86400),
        'key_prefix' => env('TELEGRAM_CONVERSATION_KEY_PREFIX', 'telegram-bot:conversation'),
    ],

    'webhook' => [
        'bot' => env('TELEGRAM_WEBHOOK_BOT', env('TELEGRAM_BOT', 'default')),

        'bot_username' => env('TELEGRAM_WEBHOOK_BOT_USERNAME'),

        'secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN'),

        'require_secret' => env('TELEGRAM_WEBHOOK_REQUIRE_SECRET', env('APP_ENV') === 'production'),

        'handler' => null,

        'middleware' => [
            // App\Telegram\Middleware\ResolveTenant::class,
        ],

        'handlers' => [
            // 'message' => App\Telegram\Handlers\MessageHandler::class,
            // 'callback_query' => App\Telegram\Handlers\CallbackQueryHandler::class,
            // 'message' => [
            //     'handler' => App\Telegram\Handlers\MessageHandler::class,
            //     'middleware' => [App\Telegram\Middleware\EnsureKnownChat::class],
            // ],
        ],

        'commands' => [
            // 'start' => App\Telegram\Commands\StartCommand::class,
            // 'start' => [
            //     'handler' => App\Telegram\Commands\StartCommand::class,
            //     'middleware' => [App\Telegram\Middleware\ThrottleCommand::class],
            // ],
        ],

        'groups' => [
            // 'admin' => [
            //     'middleware' => [App\Telegram\Middleware\EnsureAdmin::class],
            //     'commands' => [
            //         'stats' => App\Telegram\Commands\AdminStatsCommand::class,
            //     ],
            //     'handlers' => [
            //         'chat_member' => App\Telegram\Handlers\AdminChatMemberHandler::class,
            //     ],
            // ],
        ],

        'fallback_handlers' => [
            // 'message' => App\Telegram\Handlers\UnknownMessageHandler::class,
        ],

        'fallback_handler' => null,

        'discover' => [
            'commands' => [
                // App\Telegram\Commands\StartCommand::class,
            ],
            'handlers' => [
                // App\Telegram\Handlers\MessageHandler::class,
            ],
        ],

        'dispatch_event' => true,

        'queue' => [
            'enabled' => env('TELEGRAM_WEBHOOK_QUEUE_ENABLED', false),
            'connection' => env('TELEGRAM_WEBHOOK_QUEUE_CONNECTION'),
            'queue' => env('TELEGRAM_WEBHOOK_QUEUE'),
            'after_commit' => env('TELEGRAM_WEBHOOK_QUEUE_AFTER_COMMIT', false),
        ],

        'idempotency' => [
            'enabled' => env('TELEGRAM_WEBHOOK_IDEMPOTENCY_ENABLED', false),
            'store' => env('TELEGRAM_WEBHOOK_IDEMPOTENCY_STORE'),
            'ttl' => env('TELEGRAM_WEBHOOK_IDEMPOTENCY_TTL', 86400),
        ],

        'route' => [
            'enabled' => env('TELEGRAM_WEBHOOK_ROUTE_ENABLED', true),
            'uri' => env('TELEGRAM_WEBHOOK_ROUTE_URI', 'telegram-bot/webhook'),
            'name' => env('TELEGRAM_WEBHOOK_ROUTE_NAME', 'telegram-bot.webhook'),
            'middleware' => [],
        ],
    ],
];
