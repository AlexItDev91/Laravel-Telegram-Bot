<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class TelegramBotInstallCommand extends Command
{
    protected $signature = 'telegram-bot:install
        {--force : Overwrite the published package config}
        {--no-publish : Skip publishing config/telegram-bot.php}
        {--bot= : Bot config name for generated snippets}
        {--channel= : Channel config name for generated snippets}
        {--token= : Bot token used only for validation during this run}
        {--skip-token-check : Do not validate the configured or provided token}';

    protected $description = 'Install and inspect the Laravel Telegram Bot package configuration.';

    public function handle(): int
    {
        if (! (bool) $this->option('no-publish')) {
            $this->publishConfig();
        }

        $bot = $this->botNameForSnippet();
        $channel = $this->channelNameForSnippet();
        $token = $this->tokenForValidation();

        if (! (bool) $this->option('skip-token-check')) {
            $this->validateToken($token);
        }

        info('Add these values to your Laravel environment or secret store:');
        $this->line('');
        $this->line('TELEGRAM_BOT='.$bot);
        $this->line('TELEGRAM_BOT_TOKEN=<bot-token-from-botfather>');
        $this->line('TELEGRAM_BOT_API_URL=https://api.telegram.org');
        $this->line('TELEGRAM_BOT_TIMEOUT=10');
        $this->line('TELEGRAM_WEBHOOK_SECRET_TOKEN=<letters-numbers-underscore-or-dash>');
        $this->line('TELEGRAM_WEBHOOK_REQUIRE_SECRET=true');
        $this->line('TELEGRAM_'.$this->envKey($channel).'_CHAT_ID=<chat-id>');
        $this->line('TELEGRAM_'.$this->envKey($channel).'_MESSAGE_THREAD_ID=<message-thread-id-if-topic>');
        $this->line('');

        info('Add or adjust this channel mapping in config/telegram-bot.php:');
        $this->line('');
        $this->line("'channels' => [");
        $this->line("    '{$channel}' => [");
        $this->line("        'bot' => '{$bot}',");
        $this->line("        'chat_id' => env('TELEGRAM_".$this->envKey($channel)."_CHAT_ID'),");
        $this->line("        'message_thread_id' => env('TELEGRAM_".$this->envKey($channel)."_MESSAGE_THREAD_ID'),");
        $this->line('    ],');
        $this->line('],');
        $this->line('');

        outro('Run php artisan telegram-bot:updates to discover chat_id and message_thread_id values.');

        return self::SUCCESS;
    }

    private function publishConfig(): void
    {
        $this->callSilent('vendor:publish', array_filter([
            '--provider' => 'AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramBotServiceProvider',
            '--tag' => 'telegram-bot-config',
            '--force' => (bool) $this->option('force') ? true : null,
        ], static fn (mixed $value): bool => $value !== null));

        info('Published config/telegram-bot.php.');
    }

    private function botNameForSnippet(): string
    {
        $option = $this->option('bot');

        if (is_string($option) && trim($option) !== '') {
            return trim($option);
        }

        if ($this->input->isInteractive()) {
            return text(
                label: 'Bot config name',
                default: (string) config('telegram-bot.default', 'default'),
                required: true,
            );
        }

        return (string) config('telegram-bot.default', 'default');
    }

    private function channelNameForSnippet(): string
    {
        $option = $this->option('channel');

        if (is_string($option) && trim($option) !== '') {
            return trim($option);
        }

        if ($this->input->isInteractive()) {
            return text(
                label: 'Channel config name',
                default: 'inbox',
                required: true,
            );
        }

        return 'inbox';
    }

    private function tokenForValidation(): ?string
    {
        $option = $this->option('token');

        if (is_string($option) && trim($option) !== '') {
            return trim($option);
        }

        $configured = config('telegram-bot.token');

        if (is_string($configured) && trim($configured) !== '') {
            return trim($configured);
        }

        if ($this->input->isInteractive() && ! (bool) $this->option('skip-token-check') && confirm('Validate a bot token now?', false)) {
            return password(
                label: 'Bot token for validation only',
                required: true,
            );
        }

        return null;
    }

    private function validateToken(?string $token): void
    {
        if ($token === null || trim($token) === '') {
            warning('No bot token was available for validation. The command did not persist or require a token.');

            return;
        }

        try {
            $result = TelegramBotClient::make(
                token: $token,
                apiUrl: (string) config('telegram-bot.api_url', 'https://api.telegram.org'),
                timeout: (float) config('telegram-bot.timeout', 10),
            )->getMe();
        } catch (Throwable $exception) {
            warning('Bot token validation failed: '.$exception->getMessage());

            return;
        }

        if (! is_array($result)) {
            warning('Telegram returned an unexpected getMe result.');

            return;
        }

        info(sprintf(
            'Validated bot: id=%s username=%s',
            (string) ($result['id'] ?? ''),
            is_string($result['username'] ?? null) ? '@'.$result['username'] : '(no username)',
        ));
    }

    private function envKey(string $value): string
    {
        $key = strtoupper((string) preg_replace('/[^A-Za-z0-9]+/', '_', $value));
        $key = trim($key, '_');

        return $key !== '' ? $key : 'INBOX';
    }
}
