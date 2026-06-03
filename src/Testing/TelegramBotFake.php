<?php

namespace AlexItDev91\LaravelTelegramBot\Testing;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotChannelNotConfiguredException;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotResultFactory;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethods;
use AlexItDev91\LaravelTelegramBot\TelegramBotTypedApiMethods;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;

class TelegramBotFake implements TelegramBotClient, TelegramBotManager
{
    use TelegramBotApiMethods;
    use TelegramBotTypedApiMethods;

    /**
     * @var list<array{bot: string, channel: string|null, method: string, parameters: array<string, mixed>}>
     */
    private array $calls = [];

    /**
     * @var array<string, list<mixed>>
     */
    private array $results = [];

    private ?string $selectedBot = null;

    public function bot(?string $name = null): TelegramBotClient
    {
        $this->selectedBot = $name ?? 'default';

        return $this;
    }

    public function channel(string $name): TelegramBotChannel
    {
        $channels = config('telegram-bot.channels', []);

        if (! is_array($channels) || ! is_array($channels[$name] ?? null)) {
            throw new TelegramBotChannelNotConfiguredException("Telegram Bot channel [$name] is not configured.");
        }

        $config = TelegramChannelConfigData::fromArray($channels[$name]);

        return new TelegramBotFakeChannel(
            fake: $this,
            channel: $name,
            bot: $config->bot ?? 'default',
            config: $config,
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        if ($parameters instanceof TelegramBotMethodRequestData && $parameters->method() !== $methodName) {
            throw new InvalidArgumentException(sprintf(
                'Telegram Bot request DTO for method [%s] cannot be used with method [%s].',
                $parameters->method(),
                $methodName,
            ));
        }

        $botName = $this->selectedBot ?? 'default';
        $this->selectedBot = null;

        return $this->recordCall($botName, null, $methodName, $parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function callData(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        return TelegramBotResultFactory::from($method, $this->call($method, $parameters));
    }

    public function result(mixed $result, string|TelegramBotApiMethod $method = '*'): self
    {
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        $this->results[$methodName][] = $result;

        return $this;
    }

    /**
     * @return list<array{bot: string, channel: string|null, method: string, parameters: array<string, mixed>}>
     */
    public function calls(): array
    {
        return $this->calls;
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function recordCall(string $bot, ?string $channel, string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;

        $this->calls[] = [
            'bot' => $bot,
            'channel' => $channel,
            'method' => $methodName,
            'parameters' => $parameters instanceof TelegramBotRequestData ? $parameters->toArray() : $parameters,
        ];

        if (array_key_exists($methodName, $this->results) && $this->results[$methodName] !== []) {
            return array_shift($this->results[$methodName]);
        }

        if (array_key_exists('*', $this->results) && $this->results['*'] !== []) {
            return array_shift($this->results['*']);
        }

        return true;
    }

    /**
     * @param  callable(array<string, mixed>, string): bool|null  $callback
     */
    public function assertCalled(string|TelegramBotApiMethod $method, ?callable $callback = null, ?int $times = null): void
    {
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        $matching = array_values(array_filter(
            $this->calls,
            static fn (array $call): bool => $call['method'] === $methodName
                && ($callback === null || $callback($call['parameters'], $call['bot']) === true),
        ));

        if ($times !== null) {
            Assert::assertCount($times, $matching, "Expected Telegram Bot method [$methodName] to be called $times times.");

            return;
        }

        Assert::assertNotSame([], $matching, "Expected Telegram Bot method [$methodName] to be called.");
    }

    /**
     * @param  callable(array<string, mixed>, string): bool|null  $callback
     */
    public function assertSentMessage(?callable $callback = null, ?int $times = null): void
    {
        $this->assertCalled(TelegramBotApiMethod::sendMessage, $callback, $times);
    }

    /**
     * @param  callable(array<string, mixed>, string): bool|null  $callback
     */
    public function assertSentMessageToChannel(string $channel, ?callable $callback = null, ?int $times = null): void
    {
        $methodName = TelegramBotApiMethod::sendMessage->value;
        $matching = array_values(array_filter(
            $this->calls,
            static fn (array $call): bool => $call['method'] === $methodName
                && $call['channel'] === $channel
                && ($callback === null || $callback($call['parameters'], $call['bot']) === true),
        ));

        if ($times !== null) {
            Assert::assertCount($times, $matching, "Expected Telegram Bot channel [$channel] to send a message $times times.");

            return;
        }

        Assert::assertNotSame([], $matching, "Expected Telegram Bot channel [$channel] to send a message.");
    }

    public function assertNothingSent(): void
    {
        Assert::assertSame([], $this->calls, 'Expected no Telegram Bot API calls to be recorded.');
    }
}
