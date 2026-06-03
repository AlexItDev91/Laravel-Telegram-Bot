<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookFailed;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookHandled;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class TelegramWebhookProcessor
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
        $this->dispatchEvent(new TelegramWebhookReceived($update, $botName));

        try {
            $result = $this->handleWithConfiguredHandler($update, $botName);
            $this->dispatchEvent(new TelegramWebhookHandled($update, $botName));

            return $result;
        } catch (Throwable $exception) {
            $this->dispatchEvent(new TelegramWebhookFailed($update, $botName, $exception));
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
        return $this->runMiddlewarePipeline(
            $update,
            $botName,
            fn (TelegramWebhookUpdate $pipelineUpdate, string $pipelineBotName): mixed => $this->dispatchConfiguredHandler($pipelineUpdate, $pipelineBotName),
        );
    }

    private function dispatchConfiguredHandler(TelegramWebhookUpdate $update, string $botName): mixed
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

    /**
     * @param  Closure(TelegramWebhookUpdate, string): mixed  $destination
     */
    private function runMiddlewarePipeline(TelegramWebhookUpdate $update, string $botName, Closure $destination): mixed
    {
        $middleware = config('telegram-bot.webhook.middleware', []);

        if (! is_array($middleware) || $middleware === []) {
            return $destination($update, $botName);
        }

        $pipeline = array_reduce(
            array_reverse($middleware),
            fn (Closure $next, mixed $middleware): Closure => fn (TelegramWebhookUpdate $pipelineUpdate, string $pipelineBotName): mixed => $this->runMiddleware($middleware, $pipelineUpdate, $pipelineBotName, $next),
            $destination,
        );

        return $pipeline($update, $botName);
    }

    /**
     * @param  Closure(TelegramWebhookUpdate, string): mixed  $next
     */
    private function runMiddleware(mixed $middleware, TelegramWebhookUpdate $update, string $botName, Closure $next): mixed
    {
        if (is_string($middleware) && class_exists($middleware)) {
            $middleware = $this->container->make($middleware);
        }

        if ($middleware instanceof TelegramWebhookMiddleware) {
            return $middleware->process($update, $botName, $next);
        }

        if (is_callable($middleware)) {
            return $this->container->call($middleware, [
                'update' => $update,
                'botName' => $botName,
                'next' => $next,
            ]);
        }

        $this->warning('Telegram webhook middleware is configured but is not resolvable or callable.', [
            'bot' => $botName,
            'update_id' => $update->updateId(),
            'update_type' => $update->type(),
            'middleware_type' => get_debug_type($middleware),
        ]);

        return $next($update, $botName);
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

    private function dispatchEvent(object $event): void
    {
        if (! config('telegram-bot.webhook.dispatch_event', true)) {
            return;
        }

        $this->events->dispatch($event);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function warning(string $message, array $context): void
    {
        if (! config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->warning($message, $context);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function error(string $message, array $context): void
    {
        if (! config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->error($message, $context);
    }
}
