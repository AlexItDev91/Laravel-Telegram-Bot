<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotLaravelConfig;
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
        {--all : Check all configured bots and channels}
        {--skip-telegram : Skip live Telegram API checks}';

    protected $description = 'Diagnose Telegram bot configuration, webhook route, and Telegram API reachability.';

    public function handle(TelegramBotManager $manager, TelegramBotLaravelConfig $config): int
    {
        if ($this->option('all')) {
            return $this->handleAll($manager, $config);
        }

        $bot = $this->botName();
        $rows = [];
        $failed = false;

        info('Telegram Bot doctor for bot ['.$bot.']');

        foreach ($config->validationIssues() as $issue) {
            $failed = true;
            $rows[] = ['Laravel config', 'failed', $issue];
        }

        try {
            $manager->bot($bot);
            $rows[] = ['Bot config', 'ok', 'Configured bot can be resolved.'];
        } catch (Throwable $exception) {
            $failed = true;
            $rows[] = ['Bot config', 'failed', $exception->getMessage()];
        }

        $secret = $config->webhookSecretToken();
        $requireSecret = $config->webhookRequiresSecret();

        if ($requireSecret && (! is_string($secret) || $secret === '')) {
            $failed = true;
            $rows[] = ['Webhook secret', 'failed', 'Webhook secret: missing while required.'];
        } elseif (is_string($secret) && $secret !== '' && preg_match('/^[A-Za-z0-9_-]{1,256}$/', $secret) !== 1) {
            $failed = true;
            $rows[] = ['Webhook secret', 'failed', 'Webhook secret contains characters Telegram will not accept.'];
        } else {
            $rows[] = ['Webhook secret', 'ok', $secret === null || $secret === '' ? 'Not configured.' : 'Configured.'];
        }

        $routeName = $config->webhookRouteName();
        $routeEnabled = $config->webhookRouteEnabled();

        if (! $routeEnabled) {
            $rows[] = ['Webhook route', 'skipped', 'Package route auto-registration is disabled.'];
        } elseif (Route::has($routeName)) {
            $rows[] = ['Webhook route', 'ok', route($routeName, [], false)];
        } else {
            $failed = true;
            $rows[] = ['Webhook route', 'failed', 'Configured webhook route is not registered.'];
        }

        if (! $failed && ! $this->option('skip-telegram')) {
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

    private function handleAll(TelegramBotManager $manager, TelegramBotLaravelConfig $config): int
    {
        $rows = [];
        $failed = false;

        info('Telegram Bot doctor for all configured bots and channels');

        $failed = $this->appendWebhookChecks($config, $rows);

        foreach ($config->botNames() as $bot) {
            $botReady = $this->appendBotConfigCheck($manager, $config, $bot, $rows);
            $failed = ! $botReady || $failed;

            if ($botReady && ! $this->option('skip-telegram')) {
                $failed = $this->appendTelegramChecks($manager, $bot, $rows, "Bot [$bot] Telegram") || $failed;
            }
        }

        $failed = $this->appendChannelChecks($config, $rows) || $failed;

        return $this->renderResult($rows, $failed);
    }

    /**
     * @param  list<array{string, string, string}>  $rows
     */
    private function appendTelegramChecks(TelegramBotManager $manager, string $bot, array &$rows, string $labelPrefix = 'Telegram'): bool
    {
        $failed = false;

        try {
            $me = $manager->bot($bot)->call('getMe');
            $rows[] = [$labelPrefix.' getMe', 'ok', $this->botIdentity($me)];
        } catch (Throwable $exception) {
            $failed = true;
            $rows[] = [$labelPrefix.' getMe', 'failed', $exception->getMessage()];
        }

        try {
            $webhook = $manager->bot($bot)->call('getWebhookInfo');
            $rows[] = [$labelPrefix.' webhook', 'ok', $this->webhookStatus($webhook)];
        } catch (Throwable $exception) {
            $failed = true;
            $rows[] = [$labelPrefix.' webhook', 'failed', $exception->getMessage()];
        }

        return $failed;
    }

    /**
     * @param  list<array{string, string, string}>  $rows
     */
    private function appendBotConfigCheck(
        TelegramBotManager $manager,
        TelegramBotLaravelConfig $config,
        string $bot,
        array &$rows,
    ): bool {
        try {
            $botConfig = $config->bot($bot);

            if ($botConfig->token === null || trim($botConfig->token) === '') {
                $rows[] = ["Bot [$bot] config", 'failed', 'Bot token is missing.'];

                return false;
            }

            $manager->bot($bot);
            $rows[] = ["Bot [$bot] config", 'ok', 'Configured bot can be resolved.'];

            return true;
        } catch (Throwable $exception) {
            $rows[] = ["Bot [$bot] config", 'failed', $exception->getMessage()];

            return false;
        }
    }

    /**
     * @param  list<array{string, string, string}>  $rows
     */
    private function appendChannelChecks(TelegramBotLaravelConfig $config, array &$rows): bool
    {
        $failed = false;

        foreach ($config->channelNames() as $channel) {
            try {
                $channelConfig = $config->channel($channel);
                $bot = is_string($channelConfig->bot) && trim($channelConfig->bot) !== ''
                    ? $channelConfig->bot
                    : $config->defaultBot();

                $config->bot($bot);

                $rows[] = ["Channel [$channel]", 'ok', "Configured channel can be resolved for bot [$bot]."];
            } catch (Throwable $exception) {
                $failed = true;
                $rows[] = ["Channel [$channel]", 'failed', $exception->getMessage()];
            }
        }

        if ($config->channelNames() === []) {
            $rows[] = ['Channels', 'skipped', 'No channels are configured.'];
        }

        return $failed;
    }

    /**
     * @param  list<array{string, string, string}>  $rows
     */
    private function appendWebhookChecks(TelegramBotLaravelConfig $config, array &$rows): bool
    {
        $failed = false;
        $secret = $config->webhookSecretToken();
        $requireSecret = $config->webhookRequiresSecret();

        if ($requireSecret && (! is_string($secret) || $secret === '')) {
            $failed = true;
            $rows[] = ['Webhook secret', 'failed', 'Webhook secret: missing while required.'];
        } elseif (is_string($secret) && $secret !== '' && preg_match('/^[A-Za-z0-9_-]{1,256}$/', $secret) !== 1) {
            $failed = true;
            $rows[] = ['Webhook secret', 'failed', 'Webhook secret contains characters Telegram will not accept.'];
        } else {
            $rows[] = ['Webhook secret', 'ok', $secret === null || $secret === '' ? 'Not configured.' : 'Configured.'];
        }

        $routeName = $config->webhookRouteName();
        $routeEnabled = $config->webhookRouteEnabled();

        if (! $routeEnabled) {
            $rows[] = ['Webhook route', 'skipped', 'Package route auto-registration is disabled.'];
        } elseif (Route::has($routeName)) {
            $rows[] = ['Webhook route', 'ok', route($routeName, [], false)];
        } else {
            $failed = true;
            $rows[] = ['Webhook route', 'failed', 'Configured webhook route is not registered.'];
        }

        return $failed;
    }

    /**
     * @param  list<array{string, string, string}>  $rows
     */
    private function renderResult(array $rows, bool $failed): int
    {
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
