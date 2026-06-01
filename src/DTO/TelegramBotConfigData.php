<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramBotConfigData
{
    public function __construct(
        public ?string $token,
        public string $apiUrl = 'https://api.telegram.org',
        public float $timeout = 10.0,
    ) {
        //
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
