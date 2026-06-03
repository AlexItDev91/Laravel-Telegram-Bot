<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class TelegramBotSendTestCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    private const MANUAL_DESTINATION = '__manual_chat_id__';

    protected $signature = 'telegram-bot:send-test
        {--bot= : Configured bot name used with --chat-id}
        {--channel= : Configured channel name; uses its bot, chat_id, message_thread_id, and direct_messages_topic_id}
        {--chat-id= : Explicit target chat ID or @username}
        {--message-thread-id= : Forum topic message_thread_id for explicit chat targets}
        {--direct-messages-topic-id= : Direct messages topic ID for explicit chat targets}
        {--text= : Test message text}
        {--parse-mode= : Optional parse mode: MarkdownV2, HTML, or Markdown}
        {--disable-notification : Send the test message silently}
        {--protect-content : Protect the test message content from forwarding and saving}';

    protected $description = 'Send a Telegram test message to verify Laravel bot, channel, chat, and thread configuration.';

    public function handle(TelegramBotManager $manager): int
    {
        try {
            [$client, $destination, $parameters] = $this->target($manager);
            $parameters = array_filter(array_merge($parameters, [
                'text' => $this->messageText(),
                'parse_mode' => $this->parseMode(),
                'disable_notification' => $this->option('disable-notification') ?: null,
                'protect_content' => $this->option('protect-content') ?: null,
            ]), static fn (mixed $value): bool => $value !== null && $value !== '');

            $result = $client->call('sendMessage', $parameters);
        } catch (Throwable $exception) {
            warning('Failed to send Telegram test message: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! is_array($result)) {
            warning('Telegram returned an unexpected sendMessage result.');

            return self::FAILURE;
        }

        info('Telegram test message sent.');
        table(
            ['Field', 'Value'],
            [
                ['destination', $destination],
                ['message_id', $this->stringValue($result['message_id'] ?? '')],
                ['chat_id', $this->stringValue($result['chat']['id'] ?? '')],
                ['message_thread_id', $this->stringValue($result['message_thread_id'] ?? '')],
                ['direct_messages_topic_id', $this->stringValue($result['direct_messages_topic_id'] ?? '')],
            ],
        );
        outro('Delivery verified when the message is visible in the expected Telegram chat or topic.');

        return self::SUCCESS;
    }

    /**
     * @return array{0: TelegramBotClient, 1: string, 2: array<string, mixed>}
     */
    private function target(TelegramBotManager $manager): array
    {
        $channel = $this->nullableStringOption('channel');
        $chatId = $this->nullableStringOption('chat-id');

        if ($channel !== null && $chatId !== null) {
            throw new InvalidArgumentException('Use either --channel or --chat-id, not both.');
        }

        if ($channel === null && $chatId === null && $this->input->isInteractive()) {
            $channel = $this->interactiveChannel();

            if ($channel === self::MANUAL_DESTINATION) {
                $channel = null;
                $chatId = text(
                    label: 'Target chat_id or @username',
                    required: true,
                );
            }
        }

        if ($channel !== null) {
            return [
                $manager->channel($channel),
                'channel ['.$channel.']',
                [],
            ];
        }

        if ($chatId === null || trim($chatId) === '') {
            throw new InvalidArgumentException('A configured --channel or explicit --chat-id is required.');
        }

        $messageThreadId = $this->positiveIntOption('message-thread-id');
        $directMessagesTopicId = $this->positiveIntOption('direct-messages-topic-id');

        if ($messageThreadId !== null && $directMessagesTopicId !== null) {
            throw new InvalidArgumentException('Use either --message-thread-id or --direct-messages-topic-id, not both.');
        }

        return [
            $manager->bot($this->botName()),
            'chat_id ['.$chatId.']',
            [
                'chat_id' => $chatId,
                'message_thread_id' => $messageThreadId,
                'direct_messages_topic_id' => $directMessagesTopicId,
            ],
        ];
    }

    private function interactiveChannel(): string
    {
        $channels = $this->configuredChannelOptions();

        if ($channels === []) {
            return self::MANUAL_DESTINATION;
        }

        $channels[self::MANUAL_DESTINATION] = 'Enter chat_id manually';

        if (! confirm('Use a configured Telegram channel?', true)) {
            return self::MANUAL_DESTINATION;
        }

        return (string) select(
            label: 'Which channel should receive the test message?',
            options: $channels,
        );
    }

    private function messageText(): string
    {
        $message = $this->nullableStringOption('text');

        if ($message !== null) {
            return $message;
        }

        if ($this->input->isInteractive()) {
            return text(
                label: 'Test message text',
                default: 'Telegram Bot test message from Laravel.',
                required: true,
            );
        }

        return 'Telegram Bot test message from Laravel.';
    }

    private function parseMode(): ?string
    {
        $parseMode = $this->nullableStringOption('parse-mode');

        if ($parseMode === null) {
            return null;
        }

        $allowed = array_map(
            static fn (TelegramParseMode $mode): string => $mode->value,
            TelegramParseMode::cases(),
        );

        if (! in_array($parseMode, $allowed, true)) {
            throw new InvalidArgumentException('Parse mode must be one of: '.implode(', ', $allowed).'.');
        }

        return $parseMode;
    }

    private function stringValue(mixed $value): string
    {
        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return number_format($value, 0, '.', '');
        }

        return is_scalar($value) ? (string) $value : '';
    }
}
