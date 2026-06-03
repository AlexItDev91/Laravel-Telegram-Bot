<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;

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
            return ['handled' => false, 'result' => null];
        }

        return $this->dispatchHandler($handler, $update, $botName, $command);
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

        if ($handler === null || $handler === '') {
            return ['handled' => false, 'result' => null];
        }

        return $this->dispatchHandler($handler, $update, $botName);
    }

    /**
     * @return array{handled: bool, result: mixed}
     */
    private function dispatchFallback(TelegramWebhookUpdate $update, string $botName): array
    {
        $handler = config('telegram-bot.webhook.fallback_handler');

        if ($handler === null || $handler === '') {
            return ['handled' => false, 'result' => null];
        }

        return $this->dispatchHandler($handler, $update, $botName);
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
