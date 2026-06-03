<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Notifications;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use InvalidArgumentException;

readonly class TelegramBotNotificationChannel
{
    public function __construct(private readonly TelegramBotManager $telegram)
    {
        //
    }

    public function send(object $notifiable, object $notification): mixed
    {
        if (! method_exists($notification, 'toTelegram')) {
            return null;
        }

        $message = $notification->toTelegram($notifiable);

        if ($message === null) {
            return null;
        }

        $outbound = $this->outboundMessage($message);
        $route = $this->route($notifiable, $notification);

        $channel = $outbound['channel'] ?? $route['channel'];
        $parameters = $this->mergeRouteParameters($outbound['parameters'], $route['parameters']);

        if ($channel !== null) {
            return $this->telegram->channel($channel)->call($outbound['method'], $parameters);
        }

        if (! array_key_exists('chat_id', $parameters)) {
            throw new InvalidArgumentException('Telegram notification requires a chat_id route or a configured Telegram channel.');
        }

        return $this->telegram->bot($outbound['bot'] ?? $route['bot'])->call($outbound['method'], $parameters);
    }

    /**
     * @return array{method: string|TelegramBotApiMethod, parameters: array<string, mixed>, bot: string|null, channel: string|null}
     */
    private function outboundMessage(mixed $message): array
    {
        if ($message instanceof TelegramNotificationMessage) {
            return [
                'method' => $message->method(),
                'parameters' => $this->parameters($message->parameters()),
                'bot' => $message->botName(),
                'channel' => $message->channelName(),
            ];
        }

        if ($message instanceof TelegramBotRequestData) {
            return [
                'method' => $message instanceof TelegramBotMethodRequestData ? $message->method() : $this->methodForRequestData($message),
                'parameters' => $message->toArray(),
                'bot' => null,
                'channel' => null,
            ];
        }

        if (is_string($message)) {
            return [
                'method' => TelegramBotApiMethod::sendMessage,
                'parameters' => ['text' => $message],
                'bot' => null,
                'channel' => null,
            ];
        }

        if (is_array($message)) {
            return $this->arrayMessage($message);
        }

        throw new InvalidArgumentException('Telegram notification toTelegram() must return a string, array, TelegramBotRequestData, or TelegramNotificationMessage.');
    }

    /**
     * @param  array<string, mixed>  $message
     * @return array{method: string|TelegramBotApiMethod, parameters: array<string, mixed>, bot: string|null, channel: string|null}
     */
    private function arrayMessage(array $message): array
    {
        $method = $message['method'] ?? null;
        $bot = isset($message['bot']) && is_string($message['bot']) && $message['bot'] !== '' ? $message['bot'] : null;
        $channel = isset($message['channel']) && is_string($message['channel']) && $message['channel'] !== '' ? $message['channel'] : null;
        $parameters = $message['parameters'] ?? null;

        if ($parameters instanceof TelegramBotRequestData) {
            $method ??= $parameters instanceof TelegramBotMethodRequestData ? $parameters->method() : $this->methodForRequestData($parameters);
            $parameters = $parameters->toArray();
        }

        if (! is_array($parameters)) {
            $parameters = $message;
            unset($parameters['method'], $parameters['bot'], $parameters['channel']);
        }

        $method ??= TelegramBotApiMethod::sendMessage;

        return [
            'method' => $method instanceof TelegramBotApiMethod || is_string($method) ? $method : TelegramBotApiMethod::sendMessage,
            'parameters' => $parameters,
            'bot' => $bot,
            'channel' => $channel,
        ];
    }

    /**
     * @return array{bot: string|null, channel: string|null, parameters: array<string, mixed>}
     */
    private function route(object $notifiable, object $notification): array
    {
        $route = null;

        if (method_exists($notifiable, 'routeNotificationFor')) {
            $route = $notifiable->routeNotificationFor('telegram', $notification);

            if ($route === null) {
                $route = $notifiable->routeNotificationFor(self::class, $notification);
            }
        } elseif (method_exists($notifiable, 'routeNotificationForTelegram')) {
            $route = $notifiable->routeNotificationForTelegram($notification);
        }

        if (is_int($route) || is_string($route)) {
            return [
                'bot' => null,
                'channel' => null,
                'parameters' => ['chat_id' => $route],
            ];
        }

        if (! is_array($route)) {
            return [
                'bot' => null,
                'channel' => null,
                'parameters' => [],
            ];
        }

        $bot = isset($route['bot']) && is_string($route['bot']) && $route['bot'] !== '' ? $route['bot'] : null;
        $channel = isset($route['channel']) && is_string($route['channel']) && $route['channel'] !== '' ? $route['channel'] : null;
        $parameters = $route;
        unset($parameters['bot'], $parameters['channel']);

        return [
            'bot' => $bot,
            'channel' => $channel,
            'parameters' => $parameters,
        ];
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $route
     * @return array<string, mixed>
     */
    private function mergeRouteParameters(array $parameters, array $route): array
    {
        foreach ($route as $key => $value) {
            if (! array_key_exists($key, $parameters) && $value !== null && $value !== '') {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }

    /**
     * @return array<string, mixed>
     */
    private function parameters(array|TelegramBotRequestData $parameters): array
    {
        return $parameters instanceof TelegramBotRequestData ? $parameters->toArray() : $parameters;
    }

    private function methodForRequestData(TelegramBotRequestData $data): TelegramBotApiMethod
    {
        $class = $data::class;
        $shortName = substr($class, (int) strrpos($class, '\\') + 1);

        if (! str_ends_with($shortName, 'Data')) {
            throw new InvalidArgumentException('Telegram notification request DTO method could not be inferred. Use TelegramNotificationMessage::forMethod() for generic request data.');
        }

        $method = lcfirst(substr($shortName, 0, -4));
        $apiMethod = TelegramBotApiMethod::tryFrom($method);

        if ($apiMethod === null) {
            throw new InvalidArgumentException('Telegram notification request DTO method could not be inferred. Use TelegramNotificationMessage::forMethod() for generic request data.');
        }

        return $apiMethod;
    }
}
