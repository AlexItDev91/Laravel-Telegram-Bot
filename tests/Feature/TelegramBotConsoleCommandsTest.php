<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotInstallCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotMeCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotSendTestCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotUpdatesCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotWebhookDeleteCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotWebhookInfoCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotWebhookSetCommand;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Console\Kernel;
use Psr\Http\Message\RequestInterface;

class TelegramBotConsoleCommandsTest extends TestCase
{
    public function test_registers_laravel_console_commands(): void
    {
        $commands = app(Kernel::class)->all();

        foreach ([
            'telegram-bot:install' => TelegramBotInstallCommand::class,
            'telegram-bot:me' => TelegramBotMeCommand::class,
            'telegram-bot:send-test' => TelegramBotSendTestCommand::class,
            'telegram-bot:webhook:set' => TelegramBotWebhookSetCommand::class,
            'telegram-bot:webhook:delete' => TelegramBotWebhookDeleteCommand::class,
            'telegram-bot:webhook:info' => TelegramBotWebhookInfoCommand::class,
            'telegram-bot:updates' => TelegramBotUpdatesCommand::class,
        ] as $name => $class) {
            $this->assertArrayHasKey($name, $commands);
            $this->assertInstanceOf($class, $commands[$name]);
        }
    }

    public function test_install_command_prints_copy_ready_configuration_without_persisting_token(): void
    {
        $this->artisan('telegram-bot:install', [
            '--no-publish' => true,
            '--skip-token-check' => true,
            '--bot' => 'support',
            '--channel' => 'alerts',
        ])
            ->expectsOutputToContain('TELEGRAM_BOT=support')
            ->expectsOutputToContain('TELEGRAM_ALERTS_CHAT_ID=<chat-id>')
            ->expectsOutputToContain('TELEGRAM_ALERTS_DIRECT_MESSAGES_TOPIC_ID=<direct-messages-topic-id-if-needed>')
            ->expectsOutputToContain("'alerts' => [")
            ->doesntExpectOutputToContain('123456:test-token')
            ->assertSuccessful();
    }

    public function test_me_command_prints_bot_identity(): void
    {
        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode([
                'ok' => true,
                'result' => [
                    'id' => 123456,
                    'is_bot' => true,
                    'first_name' => 'Support Bot',
                    'username' => 'support_bot',
                    'can_join_groups' => true,
                    'supports_guest_queries' => true,
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:me', ['--bot' => 'default'])
            ->expectsOutputToContain('Telegram bot identity for bot [default]')
            ->expectsOutputToContain('123456')
            ->expectsOutputToContain('@support_bot')
            ->assertSuccessful();

        $this->assertSame('/bot123456:test-token/getMe', $history[0]['request']->getUri()->getPath());
        $this->assertSame([], $this->jsonRequestPayload($history[0]['request']));
    }

    public function test_send_test_command_sends_message_to_configured_channel_topic(): void
    {
        config()->set('telegram-bot.channels.alerts', [
            'bot' => 'default',
            'chat_id' => '-1009007199254740991',
            'message_thread_id' => '42',
        ]);

        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode([
                'ok' => true,
                'result' => [
                    'message_id' => 77,
                    'message_thread_id' => 42,
                    'chat' => ['id' => -1009007199254740991],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:send-test', [
            '--channel' => 'alerts',
            '--text' => 'Laravel delivery test',
            '--parse-mode' => 'HTML',
            '--disable-notification' => true,
            '--protect-content' => true,
        ])
            ->expectsOutputToContain('Telegram test message sent.')
            ->expectsOutputToContain('channel [alerts]')
            ->assertSuccessful();

        $payload = $this->jsonRequestPayload($history[0]['request']);

        $this->assertSame('/bot123456:test-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'chat_id' => '-1009007199254740991',
            'message_thread_id' => '42',
            'text' => 'Laravel delivery test',
            'parse_mode' => 'HTML',
            'disable_notification' => true,
            'protect_content' => true,
        ], $payload);
    }

    public function test_send_test_command_sends_message_to_explicit_direct_messages_topic(): void
    {
        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode([
                'ok' => true,
                'result' => [
                    'message_id' => 88,
                    'direct_messages_topic_id' => 77,
                    'chat' => ['id' => 123456789],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:send-test', [
            '--bot' => 'default',
            '--chat-id' => '123456789',
            '--direct-messages-topic-id' => '77',
            '--text' => 'Direct messages delivery test',
        ])
            ->expectsOutputToContain('Telegram test message sent.')
            ->expectsOutputToContain('chat_id [123456789]')
            ->assertSuccessful();

        $payload = $this->jsonRequestPayload($history[0]['request']);

        $this->assertSame('/bot123456:test-token/sendMessage', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'chat_id' => '123456789',
            'direct_messages_topic_id' => 77,
            'text' => 'Direct messages delivery test',
        ], $payload);
    }

    public function test_webhook_set_command_registers_webhook_with_secret_and_allowed_updates(): void
    {
        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:webhook:set', [
            '--bot' => 'default',
            '--url' => 'https://example.test/telegram-bot/webhook',
            '--secret' => 'secret-token',
            '--allowed-updates' => ['message', 'callback_query'],
            '--max-connections' => '50',
            '--drop-pending-updates' => true,
        ])
            ->expectsOutputToContain('Telegram webhook registered.')
            ->expectsOutputToContain('Secret token: configured')
            ->doesntExpectOutputToContain('secret-token')
            ->assertSuccessful();

        $payload = $this->jsonRequestPayload($history[0]['request']);

        $this->assertSame('/bot123456:test-token/setWebhook', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'url' => 'https://example.test/telegram-bot/webhook',
            'secret_token' => 'secret-token',
            'allowed_updates' => ['message', 'callback_query'],
            'max_connections' => 50,
            'drop_pending_updates' => true,
        ], $payload);
    }

    public function test_webhook_delete_command_deletes_webhook(): void
    {
        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:webhook:delete', [
            '--bot' => 'default',
            '--drop-pending-updates' => true,
            '--yes' => true,
        ])
            ->expectsOutputToContain('Telegram webhook deleted.')
            ->assertSuccessful();

        $this->assertSame('/bot123456:test-token/deleteWebhook', $history[0]['request']->getUri()->getPath());
        $this->assertSame(['drop_pending_updates' => true], $this->jsonRequestPayload($history[0]['request']));
    }

    public function test_webhook_info_command_prints_status_without_secret_values(): void
    {
        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode([
                'ok' => true,
                'result' => [
                    'url' => 'https://example.test/telegram-bot/webhook',
                    'has_custom_certificate' => false,
                    'pending_update_count' => 2,
                    'allowed_updates' => ['message'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:webhook:info', ['--bot' => 'default'])
            ->expectsOutputToContain('Telegram webhook status for bot [default]')
            ->expectsOutputToContain('https://example.test/telegram-bot/webhook')
            ->expectsOutputToContain('pending_update_count')
            ->assertSuccessful();

        $this->assertSame('/bot123456:test-token/getWebhookInfo', $history[0]['request']->getUri()->getPath());
    }

    public function test_updates_command_prints_parsed_chat_id_and_message_thread_id(): void
    {
        $history = [];
        $this->configureBotHttpClient($history, [
            new Response(200, [], json_encode([
                'ok' => true,
                'result' => [
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
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $this->artisan('telegram-bot:updates', [
            '--bot' => 'default',
            '--skip-webhook-check' => true,
            '--limit' => '5',
            '--timeout' => '0',
            '--allowed-updates' => ['message'],
        ])
            ->expectsOutputToContain('Parsed Telegram chat references for bot [default]')
            ->expectsOutputToContain('-1009007199254740991')
            ->expectsOutputToContain('42')
            ->assertSuccessful();

        $this->assertSame('/bot123456:test-token/getUpdates', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'limit' => 5,
            'timeout' => 0,
            'allowed_updates' => ['message'],
        ], $this->jsonRequestPayload($history[0]['request']));
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     * @param  list<Response>  $responses
     */
    private function configureBotHttpClient(array &$history, array $responses): void
    {
        config()->set('telegram-bot.token', '123456:test-token');
        config()->set('telegram-bot.api_url', 'https://api.telegram.test');

        $this->app->bind(ClientInterface::class, function () use (&$history, $responses): ClientInterface {
            $handler = HandlerStack::create(new MockHandler($responses));
            $handler->push(Middleware::history($history));

            return new Client([
                'handler' => $handler,
                'http_errors' => false,
            ]);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function jsonRequestPayload(RequestInterface $request): array
    {
        $payload = json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertIsArray($payload);

        return $payload;
    }
}
