<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Passport;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class PassportAuthorizationRequest implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  TelegramBotData|array<string, mixed>  $scope
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private int|string $botId,
        private TelegramBotData|array $scope,
        private string $publicKey,
        private string $nonce,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'bot_id' => $this->botId,
            'scope' => $this->scope,
            'public_key' => $this->publicKey,
            'nonce' => $this->nonce,
        ], $this->extra, ['bot_id', 'scope', 'public_key', 'nonce']);
    }
}
