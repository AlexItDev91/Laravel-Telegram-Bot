<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookDuplicateSkipped;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookQueued;
use AlexItDev91\LaravelTelegramBot\Laravel\Jobs\TelegramWebhookJob;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

readonly class TelegramWebhookReceiver
{
    public function __construct(
        private readonly Container $container,
        private readonly TelegramWebhookProcessor $processor,
        private readonly TelegramWebhookIdempotency $idempotency,
        private readonly EventDispatcher $events,
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

        if ($this->idempotency->shouldSkip($update, $botName)) {
            $this->dispatchEvent(new TelegramWebhookDuplicateSkipped($update, $botName));

            return new JsonResponse(['ok' => true, 'duplicate' => true]);
        }

        if ($this->shouldQueue()) {
            try {
                if ($this->dispatchQueued($payload, $botName)) {
                    $this->dispatchEvent(new TelegramWebhookQueued(
                        update: $update,
                        botName: $botName,
                        connection: $this->queueConnection(),
                        queue: $this->queueName(),
                    ));

                    return new JsonResponse(['ok' => true, 'queued' => true]);
                }
            } catch (Throwable $exception) {
                $this->idempotency->release($update, $botName);

                throw $exception;
            }
        }

        try {
            $handlerResult = $this->processor->process($update, $botName);
        } catch (Throwable $exception) {
            $this->idempotency->release($update, $botName);

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

    /**
     * @param  array<string, mixed>  $payload
     */
    private function dispatchQueued(array $payload, string $botName): bool
    {
        if (! $this->container->bound(BusDispatcher::class)) {
            $this->warning('Telegram webhook queue is enabled but the bus dispatcher is not available; processing synchronously.', [
                'bot' => $botName,
            ]);

            return false;
        }

        $job = new TelegramWebhookJob($payload, $botName);
        $connection = $this->queueConnection();
        $queue = $this->queueName();

        if (is_string($connection) && $connection !== '') {
            $job->onConnection($connection);
        }

        if (is_string($queue) && $queue !== '') {
            $job->onQueue($queue);
        }

        if (config('telegram-bot.webhook.queue.after_commit', false)) {
            $job->afterCommit();
        }

        $this->container->make(BusDispatcher::class)->dispatch($job);

        return true;
    }

    private function shouldQueue(): bool
    {
        return config('telegram-bot.webhook.queue.enabled', false) ? true : false;
    }

    private function queueConnection(): ?string
    {
        $connection = config('telegram-bot.webhook.queue.connection');

        return is_string($connection) && $connection !== '' ? $connection : null;
    }

    private function queueName(): ?string
    {
        $queue = config('telegram-bot.webhook.queue.queue');

        return is_string($queue) && $queue !== '' ? $queue : null;
    }

    private function dispatchEvent(object $event): void
    {
        if (! config('telegram-bot.webhook.dispatch_event', true)) {
            return;
        }

        $this->events->dispatch($event);
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
        if (! config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->warning($message, $context);
    }
}
