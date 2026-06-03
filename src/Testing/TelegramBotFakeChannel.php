<?php

namespace AlexItDev91\LaravelTelegramBot\Testing;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethods;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;

final class TelegramBotFakeChannel extends TelegramBotChannel
{
    use TelegramBotApiMethods;

    public function __construct(
        private readonly TelegramBotFake $fake,
        private readonly string $channel,
        private readonly string $bot,
        private readonly TelegramChannelConfigData $config,
    ) {
        parent::__construct($fake, $config);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $parameters = $parameters instanceof TelegramBotRequestData ? $parameters->parameters : $parameters;

        return $this->fake->recordCall(
            bot: $this->bot,
            channel: $this->channel,
            method: $method,
            parameters: array_merge($this->config->chatDefaults(), $parameters),
        );
    }
}
