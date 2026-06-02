<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns\ResolvesTelegramBotConsoleInput;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class TelegramBotWebhookSetCommand extends Command
{
    use ResolvesTelegramBotConsoleInput;

    protected $signature = 'telegram-bot:webhook:set
        {--bot= : Configured bot name}
        {--url= : Public HTTPS webhook URL}
        {--secret= : Webhook secret token; not printed}
        {--no-secret : Register the webhook without a secret token}
        {--allowed-updates=* : Telegram update type to receive; repeat the option for multiple values}
        {--max-connections= : Maximum allowed simultaneous HTTPS connections}
        {--ip-address= : Fixed IP address for Telegram webhook delivery}
        {--drop-pending-updates : Drop pending updates while setting the webhook}';

    protected $description = 'Register a Telegram webhook for a configured bot.';

    public function handle(TelegramBotManager $manager): int
    {
        $bot = $this->botName();
        $parameters = [
            'url' => $this->webhookUrl(),
            'secret_token' => $this->secretToken(),
            'allowed_updates' => $this->allowedUpdates(),
            'max_connections' => $this->positiveIntOption('max-connections'),
            'ip_address' => $this->nullableStringOption('ip-address'),
            'drop_pending_updates' => (bool) $this->option('drop-pending-updates') ?: null,
        ];
        $parameters = array_filter($parameters, static fn (mixed $value): bool => $value !== null && $value !== []);

        try {
            $manager->bot($bot)->call('setWebhook', $parameters);
        } catch (Throwable $exception) {
            warning('Failed to set Telegram webhook: '.$exception->getMessage());

            return self::FAILURE;
        }

        info('Telegram webhook registered.');
        $this->line('Bot: '.$bot);
        $this->line('URL: '.$parameters['url']);
        $this->line('Secret token: '.(isset($parameters['secret_token']) ? 'configured' : 'not configured'));
        $this->line('Allowed updates: '.(isset($parameters['allowed_updates']) ? implode(', ', $parameters['allowed_updates']) : 'all update types'));
        outro('Use php artisan telegram-bot:webhook:info to verify Telegram delivery status.');

        return self::SUCCESS;
    }

    private function webhookUrl(): string
    {
        $url = $this->nullableStringOption('url');

        if ($url === null && $this->input->isInteractive()) {
            $url = text(
                label: 'Public HTTPS webhook URL',
                default: $this->defaultWebhookUrl(),
                required: true,
                validate: fn (string $value): ?string => $this->validHttpsUrl($value) ? null : 'Enter a valid HTTPS URL.',
            );
        }

        if ($url === null || ! $this->validHttpsUrl($url)) {
            throw new InvalidArgumentException('A valid HTTPS webhook URL is required. Use --url=https://example.com/telegram-bot/webhook.');
        }

        return $url;
    }

    private function secretToken(): ?string
    {
        if ((bool) $this->option('no-secret')) {
            return null;
        }

        $secret = $this->nullableStringOption('secret');

        if ($secret === null) {
            $configured = config('telegram-bot.webhook.secret_token');
            $secret = is_string($configured) && trim($configured) !== '' ? trim($configured) : null;
        }

        if ($secret === null && $this->input->isInteractive() && confirm('Set a Telegram webhook secret token?', true)) {
            $secret = password(
                label: 'Webhook secret token',
                required: true,
                validate: fn (string $value): ?string => $this->validSecretToken($value) ? null : 'Use 1-256 chars: A-Z, a-z, 0-9, underscore, dash.',
            );
        }

        if ($secret !== null && ! $this->validSecretToken($secret)) {
            throw new InvalidArgumentException('Webhook secret token must be 1-256 chars and contain only A-Z, a-z, 0-9, underscore, and dash.');
        }

        return $secret;
    }

    /**
     * @return list<string>
     */
    private function allowedUpdates(): array
    {
        $updates = $this->stringListOption('allowed-updates');

        if ($updates === [] && $this->input->isInteractive() && confirm('Limit allowed update types?', false)) {
            /** @var list<string> $updates */
            $updates = multiselect(
                label: 'Allowed update types',
                options: $this->updateTypeOptions(),
                default: ['message', 'callback_query'],
                required: false,
            );
        }

        $known = array_fill_keys(TelegramWebhookUpdate::updateTypes(), true);

        foreach ($updates as $update) {
            if (! isset($known[$update])) {
                throw new InvalidArgumentException("Unknown Telegram update type [{$update}].");
            }
        }

        return $updates;
    }

    private function defaultWebhookUrl(): string
    {
        try {
            return route((string) config('telegram-bot.webhook.route.name', 'telegram-bot.webhook'));
        } catch (Throwable) {
            return '';
        }
    }

    private function validHttpsUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && str_starts_with(strtolower($url), 'https://');
    }

    private function validSecretToken(string $token): bool
    {
        return preg_match('/^[A-Za-z0-9_-]{1,256}$/', $token) === 1;
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
