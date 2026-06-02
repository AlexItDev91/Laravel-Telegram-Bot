<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TelegramWebhookReceiver
{
    public function __construct(
        private readonly Container $container,
        private readonly Dispatcher $events,
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    public function handle(Request $request): Response
    {
        $botName = (string) config('telegram-bot.webhook.bot', config('telegram-bot.default', 'default'));
        $payload = $this->payload($request);

        if ($payload === null || ! array_key_exists('update_id', $payload) || ! is_int($payload['update_id'])) {
            $this->warning('Telegram webhook rejected because the update payload is invalid.', [
                'bot' => $botName,
                'content_type' => $request->headers->get('content-type'),
                'content_length' => $request->headers->get('content-length'),
            ]);

            return new JsonResponse(['ok' => false, 'description' => 'Invalid Telegram webhook update payload.'], 422);
        }

        $update = TelegramWebhookUpdate::fromPayload($payload);

        if ((bool) config('telegram-bot.webhook.dispatch_event', true)) {
            $this->events->dispatch(new TelegramWebhookReceived($update, $botName));
        }

        try {
            $handlerResult = $this->handleWithConfiguredHandler($update, $botName);
        } catch (Throwable $exception) {
            $this->error('Telegram webhook handler failed.', [
                'bot' => $botName,
                'update_id' => $update->updateId(),
                'update_type' => $update->type(),
                'exception' => $exception::class,
            ]);

            throw $exception;
        }

        return $this->response($handlerResult);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function payload(Request $request): ?array
    {
        try {
            $payload = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        return is_array($payload) ? $payload : null;
    }

    private function handleWithConfiguredHandler(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $handler = config('telegram-bot.webhook.handler');

        if ($handler === null || $handler === '') {
            return null;
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

    private function response(mixed $handlerResult): Response
    {
        if ($handlerResult instanceof Response) {
            return $handlerResult;
        }

        if (is_array($handlerResult)) {
            return new JsonResponse($handlerResult);
        }

        if (is_string($handlerResult)) {
            return new Response($handlerResult);
        }

        return new JsonResponse(['ok' => true]);
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
