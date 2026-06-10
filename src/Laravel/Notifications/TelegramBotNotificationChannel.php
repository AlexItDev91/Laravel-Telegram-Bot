<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Notifications;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;
use InvalidArgumentException;
use ReflectionMethod;

readonly class TelegramBotNotificationChannel
{
    public function __construct(private TelegramBotManager $telegram)
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
        $bot = $outbound['bot'] ?? $route['bot'];
        $token = $outbound['token'] ?? $route['token'];
        $apiUrl = $outbound['api_url'] ?? $route['api_url'];
        $timeout = $outbound['timeout'] ?? $route['timeout'];
        $parameters = $this->mergeRouteParameters($outbound['parameters'], $route['parameters']);

        if ($bot !== null && $token !== null) {
            throw new InvalidArgumentException('Use either a configured Telegram bot name or a dynamic bot token, not both.');
        }

        if ($channel !== null) {
            return $this->channelFor($channel, $bot, $token, $apiUrl, $timeout)->call($outbound['method'], $parameters);
        }

        if (! array_key_exists('chat_id', $parameters)) {
            throw new InvalidArgumentException('Telegram notification requires a chat_id route or a configured Telegram channel.');
        }

        return $this->botFor($bot, $token, $apiUrl, $timeout)->call($outbound['method'], $parameters);
    }

    /**
     * @return array{method: string|TelegramBotApiMethod, parameters: array<string, mixed>, bot: string|null, token: string|null, api_url: string|null, timeout: float|null, channel: string|null}
     */
    private function outboundMessage(mixed $message): array
    {
        if ($message instanceof TelegramNotificationMessage) {
            return [
                'method' => $message->method(),
                'parameters' => $this->parameters($message->parameters()),
                'bot' => $message->botName(),
                'token' => $message->botTokenValue(),
                'api_url' => $message->apiUrl(),
                'timeout' => $message->timeout(),
                'channel' => $message->channelName(),
            ];
        }

        if ($message instanceof TelegramBotRequestData) {
            return [
                'method' => $message instanceof TelegramBotMethodRequestData ? $message->method() : $this->methodForRequestData($message),
                'parameters' => $message->toArray(),
                'bot' => null,
                'token' => null,
                'api_url' => null,
                'timeout' => null,
                'channel' => null,
            ];
        }

        if (is_string($message)) {
            return [
                'method' => TelegramBotApiMethod::sendMessage,
                'parameters' => ['text' => $message],
                'bot' => null,
                'token' => null,
                'api_url' => null,
                'timeout' => null,
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
     * @return array{method: string|TelegramBotApiMethod, parameters: array<string, mixed>, bot: string|null, token: string|null, api_url: string|null, timeout: float|null, channel: string|null}
     */
    private function arrayMessage(array $message): array
    {
        $method = $message['method'] ?? null;
        $bot = $this->optionalString($message, 'bot');
        $token = $this->optionalString($message, 'token') ?? $this->optionalString($message, 'bot_token');
        $apiUrl = $this->optionalString($message, 'api_url');
        $timeout = $this->optionalFloat($message, 'timeout');
        $channel = $this->optionalString($message, 'channel');
        $parameters = $message['parameters'] ?? null;

        if ($parameters instanceof TelegramBotRequestData) {
            $method ??= $parameters instanceof TelegramBotMethodRequestData ? $parameters->method() : $this->methodForRequestData($parameters);
            $parameters = $parameters->toArray();
        }

        if (! is_array($parameters)) {
            $parameters = $message;
            unset(
                $parameters['method'],
                $parameters['bot'],
                $parameters['token'],
                $parameters['bot_token'],
                $parameters['api_url'],
                $parameters['timeout'],
                $parameters['channel'],
            );
        }

        $method ??= TelegramBotApiMethod::sendMessage;

        return [
            'method' => $method instanceof TelegramBotApiMethod || is_string($method) ? $method : TelegramBotApiMethod::sendMessage,
            'parameters' => $parameters,
            'bot' => $bot,
            'token' => $token,
            'api_url' => $apiUrl,
            'timeout' => $timeout,
            'channel' => $channel,
        ];
    }

    /**
     * @return array{bot: string|null, token: string|null, api_url: string|null, timeout: float|null, channel: string|null, parameters: array<string, mixed>}
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
                'token' => null,
                'api_url' => null,
                'timeout' => null,
                'channel' => null,
                'parameters' => ['chat_id' => $route],
            ];
        }

        if (! is_array($route)) {
            return [
                'bot' => null,
                'token' => null,
                'api_url' => null,
                'timeout' => null,
                'channel' => null,
                'parameters' => [],
            ];
        }

        $bot = $this->optionalString($route, 'bot');
        $token = $this->optionalString($route, 'token') ?? $this->optionalString($route, 'bot_token');
        $apiUrl = $this->optionalString($route, 'api_url');
        $timeout = $this->optionalFloat($route, 'timeout');
        $channel = $this->optionalString($route, 'channel');
        $parameters = $route;
        unset($parameters['bot'], $parameters['token'], $parameters['bot_token'], $parameters['api_url'], $parameters['timeout'], $parameters['channel']);

        return [
            'bot' => $bot,
            'token' => $token,
            'api_url' => $apiUrl,
            'timeout' => $timeout,
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
     * @param  array<string, mixed>|TelegramBotRequestData  $parameters
     * @return array<string, mixed>
     */
    private function parameters(array|TelegramBotRequestData $parameters): array
    {
        return $parameters instanceof TelegramBotRequestData ? $parameters->toArray() : $parameters;
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function optionalString(array $values, string $key): ?string
    {
        $value = $values[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function optionalFloat(array $values, string $key): ?float
    {
        $value = $values[$key] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    private function channelFor(
        string $channel,
        ?string $bot,
        ?string $token,
        ?string $apiUrl,
        ?float $timeout,
    ): TelegramBotChannel {
        $channelMethod = new ReflectionMethod($this->telegram, 'channel');

        if ($channelMethod->getNumberOfParameters() > 1) {
            $resolved = $channelMethod->invokeArgs($this->telegram, [$channel, $bot, $token, $apiUrl, $timeout]);

            if ($resolved instanceof TelegramBotChannel) {
                return $resolved;
            }
        }

        if ($bot !== null || $token !== null || $apiUrl !== null || $timeout !== null) {
            throw new InvalidArgumentException('Dynamic Telegram channel routing requires a manager that supports channel bot/token overrides.');
        }

        return $this->telegram->channel($channel);
    }

    private function botFor(?string $bot, ?string $token, ?string $apiUrl, ?float $timeout): TelegramBotClientContract
    {
        if ($token === null) {
            return $this->telegram->bot($bot);
        }

        if (method_exists($this->telegram, 'botToken')) {
            $client = $this->telegram->{'botToken'}($token, $apiUrl, $timeout);

            if ($client instanceof TelegramBotClientContract) {
                return $client;
            }
        }

        throw new InvalidArgumentException('Dynamic Telegram bot token routing requires a manager that supports botToken().');
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
