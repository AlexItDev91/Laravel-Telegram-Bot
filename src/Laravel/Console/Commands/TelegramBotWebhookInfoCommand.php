<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class TelegramBotWebhookInfoCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    protected $signature = 'telegram-bot:webhook:info
        {--bot= : Configured bot name}
        {--raw : Print the raw Telegram getWebhookInfo result as JSON}';

    protected $description = 'Show Telegram webhook status for a configured bot.';

    public function handle(TelegramBotManager $manager): int
    {
        $bot = $this->botName();

        try {
            $result = $manager->bot($bot)->call('getWebhookInfo');
        } catch (Throwable $exception) {
            warning('Failed to get Telegram webhook info: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! is_array($result)) {
            warning('Telegram returned an unexpected getWebhookInfo result.');

            return self::FAILURE;
        }

        if ((bool) $this->option('raw')) {
            $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));

            return self::SUCCESS;
        }

        info('Telegram webhook status for bot ['.$bot.']');
        table(
            ['Field', 'Value'],
            [
                ['url', $this->stringValue($result['url'] ?? '')],
                ['has_custom_certificate', $this->boolValue($result['has_custom_certificate'] ?? false)],
                ['pending_update_count', $this->stringValue($result['pending_update_count'] ?? 0)],
                ['last_error_date', $this->stringValue($result['last_error_date'] ?? '')],
                ['last_error_message', $this->stringValue($result['last_error_message'] ?? '')],
                ['max_connections', $this->stringValue($result['max_connections'] ?? '')],
                ['allowed_updates', $this->allowedUpdates($result['allowed_updates'] ?? null)],
            ],
        );
        info('webhook_url='.$this->stringValue($result['url'] ?? ''));
        info('pending_update_count='.$this->stringValue($result['pending_update_count'] ?? 0));

        return self::SUCCESS;
    }

    private function boolValue(mixed $value): string
    {
        return (bool) $value ? 'yes' : 'no';
    }

    private function stringValue(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }

    private function allowedUpdates(mixed $value): string
    {
        if (! is_array($value)) {
            return 'all update types';
        }

        return implode(', ', array_map('strval', $value));
    }
}
