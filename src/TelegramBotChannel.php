<?php

namespace Aptenova\TelegramBot;

use Aptenova\TelegramBot\Contracts\TelegramBotClient;
use Aptenova\TelegramBot\DTO\TelegramBotRequestData;
use Aptenova\TelegramBot\DTO\TelegramChannelConfigData;
use Aptenova\TelegramBot\Enums\TelegramBotApiMethod;

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
