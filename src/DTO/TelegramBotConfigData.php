<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotConfigurationException;

final readonly class TelegramBotConfigData
{
    public function __construct(
        public ?string $token,
        public string $apiUrl = 'https://api.telegram.org',
        public float $timeout = 10.0,
    ) {
        if (trim($this->apiUrl) === '') {
            throw new TelegramBotConfigurationException('Telegram Bot API URL must not be empty.');
        }

        if (filter_var($this->apiUrl, FILTER_VALIDATE_URL) === false) {
            throw new TelegramBotConfigurationException('Telegram Bot API URL must be a valid URL.');
        }

        if ($this->timeout <= 0.0) {
            throw new TelegramBotConfigurationException('Telegram Bot timeout must be greater than zero.');
        }
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        return new self(
            token: isset($config['token']) ? (string) $config['token'] : null,
            apiUrl: (string) ($config['api_url'] ?? 'https://api.telegram.org'),
            timeout: (float) ($config['timeout'] ?? 10),
        );
    }
}
