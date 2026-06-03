<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Attributes\TelegramCommand as TelegramCommandAttribute;
use AlexItDev91\LaravelTelegramBot\Laravel\Attributes\TelegramUpdateHandler as TelegramUpdateHandlerAttribute;
use Closure;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;
use ReflectionClass;

readonly class TelegramWebhookDispatcher implements TelegramWebhookHandler
{
    public function __construct(
        private readonly Container $container,
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $command = TelegramWebhookCommand::fromUpdate($update);

        if ($command !== null && $command->isAddressedTo($this->botUsername())) {
            $result = $this->dispatchCommand($command, $update, $botName);

            if ($result['handled']) {
                return $result['result'];
            }
        }

        $result = $this->dispatchUpdateType($update, $botName);

        if ($result['handled']) {
            return $result['result'];
        }

        return $this->dispatchFallback($update, $botName)['result'];
    }

    /**
     * @return array{handled: bool, result: mixed}
     */
    private function dispatchCommand(TelegramWebhookCommand $command, TelegramWebhookUpdate $update, string $botName): array
    {
        $commands = config('telegram-bot.webhook.commands', []);

        if (! is_array($commands)) {
            return ['handled' => false, 'result' => null];
        }

        $handler = $commands[$command->name()] ?? $commands['*'] ?? null;

        if ($handler === null || $handler === '') {
            $handler = $this->discoverCommandHandler($command->name()) ?? $this->groupedCommandHandler($command->name());
        }

        if ($handler === null || $handler === '') {
            return ['handled' => false, 'result' => null];
        }

        return $this->dispatchHandlerEntry($handler, $update, $botName, $command);
    }

    /**
     * @return array{handled: bool, result: mixed}
     */
    private function dispatchUpdateType(TelegramWebhookUpdate $update, string $botName): array
    {
        $handlers = config('telegram-bot.webhook.handlers', []);

        if (! is_array($handlers)) {
            return ['handled' => false, 'result' => null];
        }

        $type = $update->type();
        $handler = ($type !== null ? ($handlers[$type] ?? null) : null) ?? $handlers['*'] ?? null;

        if (($handler === null || $handler === '') && $type !== null) {
            $handler = $this->discoverUpdateHandler($type) ?? $this->groupedUpdateHandler($type);
        }

        if ($handler === null || $handler === '') {
            return ['handled' => false, 'result' => null];
        }

        return $this->dispatchHandlerEntry($handler, $update, $botName);
    }

    /**
     * @return array{handled: bool, result: mixed}
     */
    private function dispatchFallback(TelegramWebhookUpdate $update, string $botName): array
    {
        $type = $update->type();
        $fallbacks = config('telegram-bot.webhook.fallback_handlers', []);
        $handler = is_array($fallbacks) && $type !== null
            ? ($fallbacks[$type] ?? $fallbacks['*'] ?? null)
            : null;

        if ($handler === null || $handler === '') {
            $handler = config('telegram-bot.webhook.fallback_handler');
        }

        if ($handler === null || $handler === '') {
            return ['handled' => false, 'result' => null];
        }

        return $this->dispatchHandlerEntry($handler, $update, $botName);
    }

    /**
     * @return array{handled: bool, result: mixed}
     */
    private function dispatchHandlerEntry(mixed $entry, TelegramWebhookUpdate $update, string $botName, ?TelegramWebhookCommand $command = null): array
    {
        $route = $this->normalizeRoute($entry);

        if ($route['handler'] === null || $route['handler'] === '') {
            return ['handled' => false, 'result' => null];
        }

        $destination = fn (TelegramWebhookUpdate $pipelineUpdate, string $pipelineBotName): array => $this->dispatchHandler(
            $route['handler'],
            $pipelineUpdate,
            $pipelineBotName,
            $command,
        );

        if ($route['middleware'] === []) {
            return $destination($update, $botName);
        }

        return [
            'handled' => true,
            'result' => $this->runMiddlewarePipeline(
                $route['middleware'],
                $update,
                $botName,
                fn (TelegramWebhookUpdate $pipelineUpdate, string $pipelineBotName): mixed => $destination($pipelineUpdate, $pipelineBotName)['result'],
            ),
        ];
    }

    /**
     * @return array{handled: bool, result: mixed}
     */
    private function dispatchHandler(mixed $handler, TelegramWebhookUpdate $update, string $botName, ?TelegramWebhookCommand $command = null): array
    {
        if (is_string($handler) && class_exists($handler)) {
            $handler = $this->container->make($handler);
        }

        if ($command !== null && $handler instanceof TelegramWebhookCommandHandler) {
            return [
                'handled' => true,
                'result' => $handler->handle($command, $update, $botName),
            ];
        }

        if ($handler instanceof TelegramWebhookHandler) {
            return [
                'handled' => true,
                'result' => $handler->handle($update, $botName),
            ];
        }

        if (is_callable($handler)) {
            return [
                'handled' => true,
                'result' => $this->container->call($handler, [
                    'command' => $command,
                    'update' => $update,
                    'botName' => $botName,
                ]),
            ];
        }

        $this->warning('Telegram webhook dispatcher handler is configured but is not resolvable or callable.', [
            'bot' => $botName,
            'update_id' => $update->updateId(),
            'update_type' => $update->type(),
            'handler_type' => get_debug_type($handler),
            'command' => $command?->name(),
        ]);

        return ['handled' => true, 'result' => null];
    }

    /**
     * @return array{handler: mixed, middleware: list<mixed>}
     */
    private function normalizeRoute(mixed $entry): array
    {
        if (! is_array($entry) || ! array_key_exists('handler', $entry)) {
            return [
                'handler' => $entry,
                'middleware' => [],
            ];
        }

        $middleware = $entry['middleware'] ?? [];

        return [
            'handler' => $entry['handler'],
            'middleware' => is_array($middleware) ? array_values($middleware) : [$middleware],
        ];
    }

    /**
     * @param  list<mixed>  $middleware
     * @param  Closure(TelegramWebhookUpdate, string): mixed  $destination
     */
    private function runMiddlewarePipeline(array $middleware, TelegramWebhookUpdate $update, string $botName, Closure $destination): mixed
    {
        if ($middleware === []) {
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

        $this->warning('Telegram webhook route middleware is configured but is not resolvable or callable.', [
            'bot' => $botName,
            'update_id' => $update->updateId(),
            'update_type' => $update->type(),
            'middleware_type' => get_debug_type($middleware),
        ]);

        return $next($update, $botName);
    }

    private function groupedCommandHandler(string $command): mixed
    {
        foreach ($this->groups() as $group) {
            $commands = is_array($group['commands'] ?? null) ? $group['commands'] : [];
            $handler = $commands[$command] ?? $commands['*'] ?? null;

            if ($handler !== null && $handler !== '') {
                return $this->withGroupMiddleware($handler, $group);
            }
        }

        return null;
    }

    private function groupedUpdateHandler(string $type): mixed
    {
        foreach ($this->groups() as $group) {
            $handlers = is_array($group['handlers'] ?? null) ? $group['handlers'] : [];
            $handler = $handlers[$type] ?? $handlers['*'] ?? null;

            if ($handler !== null && $handler !== '') {
                return $this->withGroupMiddleware($handler, $group);
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $group
     */
    private function withGroupMiddleware(mixed $handler, array $group): mixed
    {
        $groupMiddleware = is_array($group['middleware'] ?? null) ? array_values($group['middleware']) : [];
        $route = $this->normalizeRoute($handler);

        return [
            'handler' => $route['handler'],
            'middleware' => array_merge($groupMiddleware, $route['middleware']),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function groups(): array
    {
        $groups = config('telegram-bot.webhook.groups', []);

        if (! is_array($groups)) {
            return [];
        }

        return array_values(array_filter($groups, static fn (mixed $group): bool => is_array($group)));
    }

    private function discoverCommandHandler(string $command): mixed
    {
        foreach ($this->discoveredClasses('commands') as $class) {
            foreach ((new ReflectionClass($class))->getAttributes(TelegramCommandAttribute::class) as $attribute) {
                $commandAttribute = $attribute->newInstance();

                if ($commandAttribute->name === $command || $commandAttribute->name === '*') {
                    return [
                        'handler' => $class,
                        'middleware' => $commandAttribute->middleware,
                    ];
                }
            }
        }

        return null;
    }

    private function discoverUpdateHandler(string $type): mixed
    {
        foreach ($this->discoveredClasses('handlers') as $class) {
            foreach ((new ReflectionClass($class))->getAttributes(TelegramUpdateHandlerAttribute::class) as $attribute) {
                $handlerAttribute = $attribute->newInstance();

                if ($handlerAttribute->type === $type || $handlerAttribute->type === '*') {
                    return [
                        'handler' => $class,
                        'middleware' => $handlerAttribute->middleware,
                    ];
                }
            }
        }

        return null;
    }

    /**
     * @return list<class-string>
     */
    private function discoveredClasses(string $key): array
    {
        $discover = config('telegram-bot.webhook.discover', []);
        $classes = is_array($discover) && is_array($discover[$key] ?? null) ? $discover[$key] : [];

        return array_values(array_filter(
            $classes,
            static fn (mixed $class): bool => is_string($class) && class_exists($class),
        ));
    }

    private function botUsername(): ?string
    {
        $username = config('telegram-bot.webhook.bot_username');

        return is_string($username) && $username !== '' ? $username : null;
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
}
