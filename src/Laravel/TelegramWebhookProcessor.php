<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Throwable;

class TelegramWebhookProcessor
{
    public function __construct(
        private readonly Container $container,
        private readonly Dispatcher $events,
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    public function process(TelegramWebhookUpdate $update, string $botName): mixed
    {
        if ((bool) config('telegram-bot.webhook.dispatch_event', true)) {
            $this->events->dispatch(new TelegramWebhookReceived($update, $botName));
        }

        try {
            return $this->handleWithConfiguredHandler($update, $botName);
        } catch (Throwable $exception) {
            $this->error('Telegram webhook handler failed.', [
                'bot' => $botName,
                'update_id' => $update->updateId(),
                'update_type' => $update->type(),
                'exception' => $exception::class,
            ]);

            throw $exception;
        }
    }

    private function handleWithConfiguredHandler(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $handler = config('telegram-bot.webhook.handler');

        if ($handler === null || $handler === '') {
            if (! $this->hasDispatcherConfiguration()) {
                return null;
            }

            $handler = TelegramWebhookDispatcher::class;
        }

        if (is_string($handler) && class_exists($handler)) {
            $handler = $this->container->make($handler);
        }

        if ($handler instanceof TelegramWebhookHandler) {
            return $handler->handle($update, $botName);
        }

        if (is_callable($handler)) {
            return $this->container->call($handler, [
                'update' => $update,
                'botName' => $botName,
            ]);
        }

        $this->warning('Telegram webhook handler is configured but is not resolvable or callable.', [
            'bot' => $botName,
            'update_id' => $update->updateId(),
            'update_type' => $update->type(),
            'handler_type' => get_debug_type($handler),
        ]);

        return null;
    }

    private function hasDispatcherConfiguration(): bool
    {
        foreach ([
            config('telegram-bot.webhook.commands', []),
            config('telegram-bot.webhook.handlers', []),
        ] as $handlers) {
            if (is_array($handlers) && $handlers !== []) {
                return true;
            }
        }

        $fallback = config('telegram-bot.webhook.fallback_handler');

        return $fallback !== null && $fallback !== '';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function warning(string $message, array $context): void
    {
        if (! (bool) config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->warning($message, $context);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function error(string $message, array $context): void
    {
        if (! (bool) config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->error($message, $context);
    }
}
