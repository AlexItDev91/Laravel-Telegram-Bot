<?php

namespace AlexItDev91\LaravelTelegramBot;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotChannelNotConfiguredException;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotConfigResolver;
use BadMethodCallException;
use Closure;

class TelegramBotManager implements TelegramBotManagerContract
{
    /**
     * @var array<string, TelegramBotClientContract>
     */
    private array $clients = [];

    /**
     * @var Closure(TelegramBotConfigData): TelegramBotClientContract
     */
    private readonly Closure $clientFactory;

    /**
     * @param  array<string, mixed>  $config
     * @param  callable(TelegramBotConfigData): TelegramBotClientContract  $clientFactory
     */
    public function __construct(
        private readonly array $config,
        callable $clientFactory,
    ) {
        $this->clientFactory = $clientFactory(...);
    }

    #[Override]
    public function bot(?string $name = null): TelegramBotClientContract
    {
        $name ??= (string) ($this->config['default'] ?? 'default');

        return $this->clients[$name] ??= ($this->clientFactory)(TelegramBotConfigData::fromArray($this->botConfig($name)));
    }

    #[Override]
    public function channel(string $name): TelegramBotChannel
    {
        $channels = $this->config['channels'] ?? [];

        if (! is_array($channels) || ! is_array($channels[$name] ?? null)) {
            throw new TelegramBotChannelNotConfiguredException("Telegram Bot channel [$name] is not configured.");
        }

        $config = $channels[$name];

        return new TelegramBotChannel(
            bot: $this->bot(isset($config['bot']) ? (string) $config['bot'] : null),
            config: TelegramChannelConfigData::fromArray($config),
        );
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        if (! method_exists($this->bot(), $method)) {
            throw new BadMethodCallException("Telegram Bot method [$method] is not available.");
        }

        return $this->bot()->$method(...$arguments);
    }

    /**
     * @return array<string, mixed>
     */
    private function botConfig(string $name): array
    {
        return TelegramBotConfigResolver::botConfig($this->config, $name);
    }
}
