<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotNotConfiguredException;
use Throwable;

final readonly class TelegramBotLaravelConfig
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(private array $config)
    {
        //
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        return new self($config);
    }

    public function defaultBot(): string
    {
        $default = $this->config['default'] ?? 'default';

        return is_string($default) && $default !== '' ? $default : 'default';
    }

    public function bot(?string $name = null): TelegramBotConfigData
    {
        return TelegramBotConfigData::fromArray($this->botConfig($name ?? $this->defaultBot()));
    }

    public function channel(string $name): TelegramChannelConfigData
    {
        $channels = $this->config['channels'] ?? [];

        if (! is_array($channels) || ! is_array($channels[$name] ?? null)) {
            throw new TelegramBotNotConfiguredException("Telegram Bot channel [$name] is not configured.");
        }

        return TelegramChannelConfigData::fromArray($channels[$name]);
    }

    public function webhookRequiresSecret(): bool
    {
        return (bool) ($this->webhookConfig()['require_secret'] ?? false);
    }

    public function webhookSecretToken(): ?string
    {
        $secret = $this->webhookConfig()['secret_token'] ?? null;

        return is_string($secret) && $secret !== '' ? $secret : null;
    }

    public function webhookRouteEnabled(): bool
    {
        return (bool) (($this->webhookConfig()['route'] ?? [])['enabled'] ?? true);
    }

    public function webhookRouteName(): string
    {
        $name = ($this->webhookConfig()['route'] ?? [])['name'] ?? 'telegram-bot.webhook';

        return is_string($name) && $name !== '' ? $name : 'telegram-bot.webhook';
    }

    public function webhookRouteUri(): string
    {
        $uri = ($this->webhookConfig()['route'] ?? [])['uri'] ?? 'telegram-bot/webhook';

        return trim(is_string($uri) && $uri !== '' ? $uri : 'telegram-bot/webhook', '/');
    }

    /**
     * @return list<string>
     */
    public function validationIssues(): array
    {
        $issues = [];

        try {
            $bot = $this->bot();

            if ($bot->token === null || trim($bot->token) === '') {
                $issues[] = 'Default bot token is missing.';
            }
        } catch (Throwable $exception) {
            $issues[] = $exception->getMessage();
        }

        foreach ($this->configuredChannelNames() as $channel) {
            try {
                $this->channel($channel);
            } catch (Throwable $exception) {
                $issues[] = "Channel [$channel]: ".$exception->getMessage();
            }
        }

        $secret = $this->webhookSecretToken();

        if ($this->webhookRequiresSecret() && $secret === null) {
            $issues[] = 'Webhook secret is required but missing.';
        }

        if ($secret !== null && preg_match('/^[A-Za-z0-9_-]{1,256}$/', $secret) !== 1) {
            $issues[] = 'Webhook secret contains characters Telegram will not accept.';
        }

        if ($this->webhookRouteEnabled() && $this->webhookRouteUri() === '') {
            $issues[] = 'Webhook route URI must not be empty when the package route is enabled.';
        }

        return $issues;
    }

    /**
     * @return list<string>
     */
    private function configuredChannelNames(): array
    {
        $channels = $this->config['channels'] ?? [];

        return is_array($channels) ? array_values(array_filter(array_keys($channels), 'is_string')) : [];
    }

    /**
     * @return array<string, mixed>
     */
    private function botConfig(string $name): array
    {
        $bots = $this->config['bots'] ?? [];

        if (is_array($bots) && is_array($bots[$name] ?? null)) {
            return array_merge($this->sharedBotConfig(), $this->configuredBotValues($bots[$name]));
        }

        if ($name === 'default') {
            return $this->sharedBotConfig();
        }

        throw new TelegramBotNotConfiguredException("Telegram Bot [$name] is not configured.");
    }

    /**
     * @return array<string, mixed>
     */
    private function sharedBotConfig(): array
    {
        return [
            'token' => $this->config['token'] ?? null,
            'api_url' => $this->config['api_url'] ?? 'https://api.telegram.org',
            'timeout' => $this->config['timeout'] ?? 10,
        ];
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private function configuredBotValues(array $config): array
    {
        return array_filter($config, static fn (mixed $value): bool => $value !== null && $value !== '');
    }

    /**
     * @return array<string, mixed>
     */
    private function webhookConfig(): array
    {
        $webhook = $this->config['webhook'] ?? [];

        return is_array($webhook) ? $webhook : [];
    }
}
