<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

class TelegramBotChannel implements TelegramBotClient
{
    use TelegramBotApiMethods;

    public function __construct(
        private readonly TelegramBotClient $bot,
        private readonly TelegramChannelConfigData $config,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $parameters = $parameters instanceof TelegramBotRequestData ? $parameters->parameters : $parameters;

        return $this->bot->call($method, array_merge(
            $this->config->chatDefaults(),
            $parameters,
        ));
    }
}
