<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramWebhookReceiver
{
    public function __construct(
        private readonly Container $container,
        private readonly Dispatcher $events,
    ) {
        //
    }

    public function handle(Request $request): Response
    {
        $payload = $this->payload($request);

        if ($payload === null || ! array_key_exists('update_id', $payload)) {
            return new JsonResponse(['ok' => false, 'description' => 'Invalid Telegram webhook update payload.'], 422);
        }

        $botName = (string) config('telegram-bot.webhook.bot', config('telegram-bot.default', 'default'));
        $update = TelegramWebhookUpdate::fromPayload($payload);

        if ((bool) config('telegram-bot.webhook.dispatch_event', true)) {
            $this->events->dispatch(new TelegramWebhookReceived($update, $botName));
        }

        return $this->response($this->handleWithConfiguredHandler($update, $botName));
    }

    /**
     * @return array<string, mixed>|null
     */
    private function payload(Request $request): ?array
    {
        $payload = json_decode($request->getContent(), true);

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
}
