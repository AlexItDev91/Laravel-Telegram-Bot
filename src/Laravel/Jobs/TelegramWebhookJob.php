<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Jobs;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;

class TelegramWebhookJob implements ShouldQueue
{
    public ?string $connection = null;

    public ?string $queue = null;

    public bool $afterCommit = false;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public array $payload,
        public string $botName,
    ) {
        //
    }

    public function handle(TelegramWebhookProcessor $processor): void
    {
        $processor->process(TelegramWebhookUpdate::fromPayload($this->payload), $this->botName);
    }

    public function onConnection(?string $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    public function onQueue(?string $queue): self
    {
        $this->queue = $queue;

        return $this;
    }

    public function afterCommit(): self
    {
        $this->afterCommit = true;

        return $this;
    }
}
