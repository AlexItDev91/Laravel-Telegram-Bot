<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class TelegramBotWebhookDeleteCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    protected $signature = 'telegram-bot:webhook:delete
        {--bot= : Configured bot name}
        {--drop-pending-updates : Drop all pending updates}
        {--yes : Skip confirmation}';

    protected $description = 'Delete the Telegram webhook for a configured bot.';

    public function handle(TelegramBotManager $manager): int
    {
        $bot = $this->botName();

        if (! $this->option('yes') && $this->input->isInteractive()) {
            $confirmed = confirm(
                label: 'Delete the Telegram webhook for bot ['.$bot.']?',
                default: false,
            );

            if (! $confirmed) {
                warning('Webhook deletion cancelled.');

                return self::FAILURE;
            }
        }

        try {
            $manager->bot($bot)->call('deleteWebhook', [
                'drop_pending_updates' => $this->option('drop-pending-updates'),
            ]);
        } catch (Throwable $exception) {
            warning('Failed to delete Telegram webhook: '.$exception->getMessage());

            return self::FAILURE;
        }

        info('Telegram webhook deleted.');
        $this->line('Bot: '.$bot);
        $this->line('Dropped pending updates: '.($this->option('drop-pending-updates') ? 'yes' : 'no'));

        return self::SUCCESS;
    }
}
