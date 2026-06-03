<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\Support\TelegramUpdateChatDiscovery;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class TelegramBotUpdatesCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    protected $signature = 'telegram-bot:updates
        {--bot= : Configured bot name}
        {--offset= : Telegram update offset}
        {--limit=10 : Maximum number of updates to fetch}
        {--timeout=0 : Long-polling timeout in seconds}
        {--allowed-updates=* : Telegram update type to fetch; repeat the option for multiple values}
        {--delete-webhook : Delete the active webhook before polling updates}
        {--skip-webhook-check : Do not check whether a webhook is currently configured}
        {--raw : Print the raw Telegram getUpdates result as JSON}';

    protected $description = 'Fetch Telegram updates and print parsed chat_id and message_thread_id values.';

    public function handle(TelegramBotManager $manager, TelegramUpdateChatDiscovery $discovery): int
    {
        $bot = $this->botName();
        $client = $manager->bot($bot);

        if (! $this->option('skip-webhook-check')) {
            $this->handleActiveWebhook($manager, $bot);
        }

        try {
            $updates = $client->call('getUpdates', $this->getUpdatesParameters());
        } catch (Throwable $exception) {
            warning('Failed to get Telegram updates: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! is_array($updates)) {
            warning('Telegram returned an unexpected getUpdates result.');

            return self::FAILURE;
        }

        /** @var list<array<string, mixed>> $updates */
        $updates = array_values(array_filter($updates, 'is_array'));

        if ($this->option('raw')) {
            $this->line(json_encode($updates, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
        }

        $rows = $discovery->rows($updates);

        if ($rows === []) {
            warning('No chat references were found in the fetched updates.');
            $this->line('Send a new message in the target chat or topic, then run this command again.');

            return self::SUCCESS;
        }

        $this->line('Copy-ready values:');
        foreach ($discovery->envLines($rows) as $line) {
            $this->line($line);
        }

        info('Parsed Telegram chat references for bot ['.$bot.']');
        table(
            ['Update', 'Type', 'Source', 'Chat ID', 'Thread ID', 'DM Topic ID', 'Message ID', 'Chat Type', 'Title'],
            array_map(static fn (array $row): array => [
                $row['update_id'],
                $row['update_type'],
                $row['source'],
                $row['chat_id'],
                $row['message_thread_id'],
                $row['direct_messages_topic_id'],
                $row['message_id'],
                $row['chat_type'],
                $row['chat_title'],
            ], $rows),
        );

        outro('Use chat_id with channel mappings, and message_thread_id when the destination is a forum topic.');

        return self::SUCCESS;
    }

    /**
     * @return array<string, mixed>
     */
    private function getUpdatesParameters(): array
    {
        $parameters = [
            'offset' => $this->nonNegativeIntOption('offset'),
            'limit' => $this->positiveIntOption('limit') ?? 10,
            'timeout' => $this->nonNegativeIntOption('timeout') ?? 0,
            'allowed_updates' => $this->allowedUpdates(),
        ];

        return array_filter($parameters, static fn (mixed $value): bool => $value !== null && $value !== []);
    }

    private function handleActiveWebhook(TelegramBotManager $manager, string $bot): void
    {
        try {
            $webhook = $manager->bot($bot)->call('getWebhookInfo');
        } catch (Throwable $exception) {
            warning('Could not check webhook status before getUpdates: '.$exception->getMessage());

            return;
        }

        if (! is_array($webhook) || ! is_string($webhook['url'] ?? null) || trim($webhook['url']) === '') {
            return;
        }

        warning('Telegram getUpdates and webhooks are mutually exclusive. Active webhook: '.$webhook['url']);

        $delete = $this->option('delete-webhook');

        if (! $delete && $this->input->isInteractive()) {
            $delete = confirm('Delete this webhook before polling updates?', false);
        }

        if (! $delete) {
            return;
        }

        try {
            $manager->bot($bot)->call('deleteWebhook', ['drop_pending_updates' => false]);
            info('Deleted active webhook before polling updates.');
        } catch (Throwable $exception) {
            warning('Failed to delete active webhook before polling updates: '.$exception->getMessage());
        }
    }

    /**
     * @return list<string>
     */
    private function allowedUpdates(): array
    {
        $updates = $this->stringListOption('allowed-updates');

        if ($updates === [] && $this->input->isInteractive() && confirm('Limit update types for this polling request?', false)) {
            /** @var list<string> $updates */
            $updates = multiselect(
                label: 'Allowed update types',
                options: $this->updateTypeOptions(),
                default: ['message', 'channel_post', 'callback_query'],
                required: false,
            );
        }

        $known = array_fill_keys(TelegramWebhookUpdate::updateTypes(), true);

        foreach ($updates as $update) {
            if (! isset($known[$update])) {
                throw new InvalidArgumentException("Unknown Telegram update type [$update].");
            }
        }

        return $updates;
    }

    /**
     * @return array<string, string>
     */
    private function updateTypeOptions(): array
    {
        $options = [];

        foreach (TelegramWebhookUpdate::updateTypes() as $type) {
            $options[$type] = $type;
        }

        return $options;
    }
}
