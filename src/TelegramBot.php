<?php

namespace AlexItDev91\LaravelTelegramBot;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotResultFactory;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient as ConcreteTelegramBotClient;
use InvalidArgumentException;
use ReflectionMethod;

class TelegramBot implements TelegramBotManager
{
    use TelegramBotApiMethods;
    use TelegramBotTypedApiMethods;

    public function __construct(
        private readonly TelegramBotManager $manager,
    ) {
    }

    #[Override]
    public function bot(?string $name = null): TelegramBotClient
    {
        return $this->manager->bot($name);
    }

    public function botToken(string $token, ?string $apiUrl = null, ?float $timeout = null): TelegramBotClient
    {
        if (method_exists($this->manager, 'botToken')) {
            $client = $this->manager->{'botToken'}($token, $apiUrl, $timeout);

            if ($client instanceof TelegramBotClient) {
                return $client;
            }
        }

        return ConcreteTelegramBotClient::make($token, $apiUrl ?? 'https://api.telegram.org', $timeout ?? 10.0);
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
        $channelMethod = new ReflectionMethod($this->manager, 'channel');

        if ($channelMethod->getNumberOfParameters() > 1) {
            $channel = $channelMethod->invokeArgs($this->manager, [$name, $bot, $token, $apiUrl, $timeout]);

            if ($channel instanceof TelegramBotChannel) {
                return $channel;
            }
        }

        if ($bot !== null || $token !== null || $apiUrl !== null || $timeout !== null) {
            throw new InvalidArgumentException('Dynamic Telegram channel routing requires a manager that supports channel bot/token overrides.');
        }

        return $this->manager->channel($name);
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
        if (method_exists($this->manager, 'to')) {
            $channel = $this->manager->{'to'}($chatId, $bot, $token, $messageThreadId, $directMessagesTopicId, $apiUrl, $timeout);

            if ($channel instanceof TelegramBotChannel) {
                return $channel;
            }
        }

        if ($bot !== null && trim($bot) !== '' && $token !== null && trim($token) !== '') {
            throw new InvalidArgumentException('Use either a configured Telegram bot name or a dynamic bot token, not both.');
        }

        $hasDynamicToken = $token !== null && trim($token) !== '';

        return new TelegramBotChannel(
            bot: $hasDynamicToken ? $this->botToken($token, $apiUrl, $timeout) : $this->bot($bot),
            config: new TelegramChannelConfigData(
                bot: $bot,
                chatId: $chatId,
                messageThreadId: $messageThreadId,
                directMessagesTopicId: $directMessagesTopicId,
            ),
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        return $this->bot()->call($method, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function callData(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        return TelegramBotResultFactory::from($method, $this->call($method, $parameters));
    }

    public function send(TelegramMessage $message): mixed
    {
        if (! $message->hasChatId()) {
            throw new InvalidArgumentException('Telegram fluent messages sent through the default bot must define a chat_id with to().');
        }

        return $this->call($message->method(), $message->payload());
    }
}
