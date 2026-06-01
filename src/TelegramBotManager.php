<?php

namespace Aptenova\TelegramBot;

use Aptenova\TelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use Aptenova\TelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use Aptenova\TelegramBot\DTO\TelegramBotConfigData;
use Aptenova\TelegramBot\DTO\TelegramChannelConfigData;
use Aptenova\TelegramBot\Exceptions\TelegramBotChannelNotConfiguredException;
use Aptenova\TelegramBot\Exceptions\TelegramBotNotConfiguredException;
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
        $this->clientFactory = Closure::fromCallable($clientFactory);
    }

    public function bot(?string $name = null): TelegramBotClientContract
    {
        $name ??= (string) ($this->config['default'] ?? 'default');

        return $this->clients[$name] ??= ($this->clientFactory)(TelegramBotConfigData::fromArray($this->botConfig($name)));
    }

    public function channel(string $name): TelegramBotChannel
    {
        $channels = $this->config['channels'] ?? [];

        if (! is_array($channels) || ! is_array($channels[$name] ?? null)) {
            throw new TelegramBotChannelNotConfiguredException("Telegram Bot channel [{$name}] is not configured.");
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
            throw new BadMethodCallException("Telegram Bot method [{$method}] is not available.");
        }

        return $this->bot()->{$method}(...$arguments);
    }

    /**
     * @return array<string, mixed>
     */
    private function botConfig(string $name): array
    {
        $bots = $this->config['bots'] ?? [];

        if (is_array($bots) && is_array($bots[$name] ?? null)) {
            return array_merge($this->sharedBotConfig(), $bots[$name]);
        }

        if ($name === 'default') {
            return $this->sharedBotConfig();
        }

        throw new TelegramBotNotConfiguredException("Telegram Bot [{$name}] is not configured.");
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
}
