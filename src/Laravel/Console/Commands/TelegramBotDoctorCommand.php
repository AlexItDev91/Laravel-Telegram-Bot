<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Throwable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class TelegramBotDoctorCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    protected $signature = 'telegram-bot:doctor
        {--bot= : Configured bot name}
        {--skip-telegram : Skip live Telegram API checks}';

    protected $description = 'Diagnose Telegram bot configuration, webhook route, and Telegram API reachability.';

    public function handle(TelegramBotManager $manager): int
    {
        $bot = $this->botName();
        $rows = [];
        $failed = false;

        info('Telegram Bot doctor for bot ['.$bot.']');

        try {
            $manager->bot($bot);
            $rows[] = ['Bot config', 'ok', 'Configured bot can be resolved.'];
        } catch (Throwable $exception) {
            $failed = true;
            $rows[] = ['Bot config', 'failed', $exception->getMessage()];
        }

        $secret = config('telegram-bot.webhook.secret_token');
        $requireSecret = (bool) config('telegram-bot.webhook.require_secret', config('app.env') === 'production');

        if ($requireSecret && (! is_string($secret) || $secret === '')) {
            $failed = true;
            $rows[] = ['Webhook secret', 'failed', 'Webhook secret: missing while required.'];
        } elseif (is_string($secret) && $secret !== '' && preg_match('/^[A-Za-z0-9_-]{1,256}$/', $secret) !== 1) {
            $failed = true;
            $rows[] = ['Webhook secret', 'failed', 'Webhook secret contains characters Telegram will not accept.'];
        } else {
            $rows[] = ['Webhook secret', 'ok', $secret === null || $secret === '' ? 'Not configured.' : 'Configured.'];
        }

        $routeName = config('telegram-bot.webhook.route.name', 'telegram-bot.webhook');
        $routeEnabled = (bool) config('telegram-bot.webhook.route.enabled', true);

        if (! $routeEnabled) {
            $rows[] = ['Webhook route', 'skipped', 'Package route auto-registration is disabled.'];
        } elseif (is_string($routeName) && Route::has($routeName)) {
            $rows[] = ['Webhook route', 'ok', route($routeName, [], false)];
        } else {
            $failed = true;
            $rows[] = ['Webhook route', 'failed', 'Configured webhook route is not registered.'];
        }

        if (! (bool) $this->option('skip-telegram') && ! $failed) {
            $failed = $this->appendTelegramChecks($manager, $bot, $rows);
        }

        foreach ($rows as $row) {
            $this->line($row[0].': '.$row[1].' - '.$row[2]);
        }

        table(['Check', 'Status', 'Details'], $rows);

        if ($failed) {
            warning('Telegram Bot doctor found issues.');

            return self::FAILURE;
        }

        info('Telegram Bot doctor completed successfully.');

        return self::SUCCESS;
    }

    /**
     * @param  list<array{string, string, string}>  $rows
     */
    private function appendTelegramChecks(TelegramBotManager $manager, string $bot, array &$rows): bool
    {
        $failed = false;

        try {
            $me = $manager->bot($bot)->call('getMe');
            $rows[] = ['Telegram getMe', 'ok', $this->botIdentity($me)];
        } catch (Throwable $exception) {
            $failed = true;
            $rows[] = ['Telegram getMe', 'failed', $exception->getMessage()];
        }

        try {
            $webhook = $manager->bot($bot)->call('getWebhookInfo');
            $rows[] = ['Telegram webhook', 'ok', $this->webhookStatus($webhook)];
        } catch (Throwable $exception) {
            $failed = true;
            $rows[] = ['Telegram webhook', 'failed', $exception->getMessage()];
        }

        return $failed;
    }

    private function botIdentity(mixed $value): string
    {
        if (! is_array($value)) {
            return 'Unexpected getMe result.';
        }

        $username = $value['username'] ?? null;
        $id = $value['id'] ?? null;

        return trim(($id !== null ? 'id='.$this->stringValue($id) : '').' '.(is_string($username) ? '@'.ltrim($username, '@') : ''));
    }

    private function webhookStatus(mixed $value): string
    {
        if (! is_array($value)) {
            return 'Unexpected getWebhookInfo result.';
        }

        $url = $value['url'] ?? '';
        $pending = $value['pending_update_count'] ?? 0;

        return 'url='.$this->stringValue($url).' pending_update_count='.$this->stringValue($pending);
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
