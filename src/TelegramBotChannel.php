<?php

namespace AlexItDev91\LaravelTelegramBot;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotResultFactory;

class TelegramBotChannel implements TelegramBotClient
{
    use TelegramBotApiMethods;
    use TelegramBotTypedApiMethods;

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
        if ($parameters instanceof TelegramBotMethodRequestData) {
            $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;

            if ($parameters->method() !== $methodName) {
                throw new \InvalidArgumentException("Telegram Bot request DTO for method [{$parameters->method()}] cannot be used with method [{$methodName}].");
            }
        }

        $parameters = $parameters instanceof TelegramBotRequestData ? $parameters->parameters : $parameters;

        return $this->bot->call($method, array_merge(
            $this->config->chatDefaults(),
            $parameters,
        ));
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function callData(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        return TelegramBotResultFactory::from($method, $this->call($method, $parameters));
    }
}
