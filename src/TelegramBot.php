<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotResultFactory;

class TelegramBot implements TelegramBotManager
{
    use TelegramBotApiMethods;
    use TelegramBotTypedApiMethods;

    public function __construct(
        private readonly TelegramBotManager $manager,
    ) {
    }

    public function bot(?string $name = null): TelegramBotClient
    {
        return $this->manager->bot($name);
    }

    public function channel(string $name): TelegramBotChannel
    {
        return $this->manager->channel($name);
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
}
