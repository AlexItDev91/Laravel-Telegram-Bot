<?php

namespace AlexItDev91\LaravelTelegramBot;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotChannelNotConfiguredException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotConfigurationException;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotConfigResolver;
use BadMethodCallException;
use Closure;
use InvalidArgumentException;

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

    public function botToken(string $token, ?string $apiUrl = null, ?float $timeout = null): TelegramBotClientContract
    {
        if (trim($token) === '') {
            throw new TelegramBotConfigurationException('Telegram Bot token is not configured.');
        }

        return ($this->clientFactory)(new TelegramBotConfigData(
            token: $token,
            apiUrl: $apiUrl ?? (string) ($this->config['api_url'] ?? 'https://api.telegram.org'),
            timeout: $timeout ?? (float) ($this->config['timeout'] ?? 10),
        ));
    }

    #[Override]
    public function channel(
        string $name,
        ?string $bot = null,
        ?string $token = null,
        ?string $apiUrl = null,
        ?float $timeout = null,
    ): TelegramBotChannel
    {
        $channels = $this->config['channels'] ?? [];

        if (! is_array($channels) || ! is_array($channels[$name] ?? null)) {
            throw new TelegramBotChannelNotConfiguredException("Telegram Bot channel [$name] is not configured.");
        }

        $config = $channels[$name];
        $configuredBot = isset($config['bot']) ? (string) $config['bot'] : null;
        $hasDynamicToken = $token !== null && trim($token) !== '';

        return new TelegramBotChannel(
            bot: $this->clientFor(
                bot: $hasDynamicToken ? $bot : ($bot ?? $configuredBot),
                token: $token,
                apiUrl: $apiUrl,
                timeout: $timeout,
            ),
            config: TelegramChannelConfigData::fromArray($config),
        );
    }

    public function to(
        string|int $chatId,
        ?string $bot = null,
        ?string $token = null,
        string|int|null $messageThreadId = null,
        string|int|null $directMessagesTopicId = null,
        ?string $apiUrl = null,
        ?float $timeout = null,
    ): TelegramBotChannel {
        return new TelegramBotChannel(
            bot: $this->clientFor($bot, $token, $apiUrl, $timeout),
            config: new TelegramChannelConfigData(
                bot: $bot,
                chatId: $chatId,
                messageThreadId: $messageThreadId,
                directMessagesTopicId: $directMessagesTopicId,
            ),
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

    private function clientFor(
        ?string $bot,
        ?string $token,
        ?string $apiUrl = null,
        ?float $timeout = null,
    ): TelegramBotClientContract {
        $bot = $bot !== null && trim($bot) !== '' ? $bot : null;
        $token = $token !== null && trim($token) !== '' ? $token : null;

        if ($bot !== null && $token !== null) {
            throw new InvalidArgumentException('Use either a configured Telegram bot name or a dynamic bot token, not both.');
        }

        if ($token !== null) {
            return $this->botToken($token, $apiUrl, $timeout);
        }

        return $this->bot($bot);
    }
}
