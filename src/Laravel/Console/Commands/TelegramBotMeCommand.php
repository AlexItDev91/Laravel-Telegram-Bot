<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class TelegramBotMeCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    protected $signature = 'telegram-bot:me
        {--bot= : Configured bot name}
        {--raw : Print the raw Telegram getMe result as JSON}';

    protected $description = 'Show Telegram bot identity for a configured bot.';

    public function handle(TelegramBotManager $manager): int
    {
        $bot = $this->botName();

        try {
            $result = $manager->bot($bot)->call('getMe');
        } catch (Throwable $exception) {
            warning('Failed to get Telegram bot identity: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! is_array($result)) {
            warning('Telegram returned an unexpected getMe result.');

            return self::FAILURE;
        }

        if ((bool) $this->option('raw')) {
            $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));

            return self::SUCCESS;
        }

        info('Telegram bot identity for bot ['.$bot.']');
        table(
            ['Field', 'Value'],
            [
                ['id', $this->stringValue($result['id'] ?? '')],
                ['is_bot', $this->boolValue($result['is_bot'] ?? false)],
                ['first_name', $this->stringValue($result['first_name'] ?? '')],
                ['username', $this->username($result['username'] ?? null)],
                ['can_join_groups', $this->boolValue($result['can_join_groups'] ?? false)],
                ['can_read_all_group_messages', $this->boolValue($result['can_read_all_group_messages'] ?? false)],
                ['supports_inline_queries', $this->boolValue($result['supports_inline_queries'] ?? false)],
                ['can_connect_to_business', $this->boolValue($result['can_connect_to_business'] ?? false)],
                ['has_main_web_app', $this->boolValue($result['has_main_web_app'] ?? false)],
                ['supports_guest_queries', $this->boolValue($result['supports_guest_queries'] ?? false)],
            ],
        );
        info('bot_id='.$this->stringValue($result['id'] ?? ''));
        info('bot_username='.$this->username($result['username'] ?? null));

        return self::SUCCESS;
    }

    private function boolValue(mixed $value): string
    {
        return (bool) $value ? 'yes' : 'no';
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

    private function username(mixed $value): string
    {
        return is_string($value) && trim($value) !== ''
            ? '@'.ltrim($value, '@')
            : '';
    }
}
