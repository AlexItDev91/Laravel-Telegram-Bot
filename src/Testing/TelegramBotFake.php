<?php

namespace AlexItDev91\LaravelTelegramBot\Testing;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotChannelNotConfiguredException;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotResultFactory;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethods;
use AlexItDev91\LaravelTelegramBot\TelegramBotTypedApiMethods;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use Throwable;

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

    #[\Override]
    public function bot(?string $name = null): TelegramBotClient
    {
        $this->selectedBot = $name ?? 'default';

        return $this;
    }

    #[\Override]
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
    #[\Override]
    public function call(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): mixed
    {
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        if ($parameters instanceof TelegramBotMethodRequest && $parameters->method() !== $methodName) {
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
     * @param  array<string, mixed>|TelegramBotRequestData|callable(array<string, mixed>, string): bool|null  $expected
     */
    public function assertSent(string|TelegramBotApiMethod $method, array|TelegramBotRequestData|callable|null $expected = null, ?int $times = null): void
    {
        $callback = match (true) {
            $expected instanceof TelegramBotRequestData => $this->payloadMatcher($expected->toArray()),
            is_array($expected) => $this->payloadMatcher($expected),
            is_callable($expected) => $expected,
            default => null,
        };

        $this->assertCalled($method, $callback, $times);
    }

    /**
     * @param  list<string|TelegramBotApiMethod>  $methods
     */
    public function assertSentSequence(array $methods): void
    {
        Assert::assertSame(
            array_map(
                static fn (string|TelegramBotApiMethod $method): string => $method instanceof TelegramBotApiMethod ? $method->value : $method,
                $methods,
            ),
            array_map(static fn (array $call): string => $call['method'], $this->calls),
            'Expected Telegram Bot API calls to match the given method sequence.',
        );
    }

    /**
     * @param  class-string<TelegramBotRequestData>  $requestClass
     */
    public function assertSentTypedPayload(string|TelegramBotApiMethod $method, string $requestClass, ?int $times = null): void
    {
        $this->assertSent($method, function (array $parameters) use ($requestClass): bool {
            try {
                new $requestClass($parameters);

                return true;
            } catch (Throwable) {
                return false;
            }
        }, $times);
    }

    public function assertNoTokenLeakage(?string $token = null): void
    {
        $tokens = array_values(array_filter(array_merge(
            $token !== null ? [$token] : [],
            $this->configuredTokens(),
        ), static fn (mixed $value): bool => is_string($value) && $value !== ''));

        $encodedCalls = json_encode($this->calls, JSON_THROW_ON_ERROR);

        foreach ($tokens as $configuredToken) {
            Assert::assertStringNotContainsString($configuredToken, $encodedCalls, 'Expected recorded Telegram Bot calls not to leak bot tokens.');
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function fakeWebhookUpdate(array $payload): TelegramWebhookUpdate
    {
        return TelegramWebhookUpdate::fromPayload($payload);
    }

    public function assertConversationState(TelegramConversationManager $manager, string $key, string $state): void
    {
        Assert::assertSame($state, $manager->get($key)?->state(), "Expected Telegram conversation [$key] to be in state [$state].");
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

    /**
     * @param  array<string, mixed>  $expected
     * @return callable(array<string, mixed>, string): bool
     */
    private function payloadMatcher(array $expected): callable
    {
        return static fn (array $parameters): bool => array_intersect_assoc($expected, $parameters) === $expected;
    }

    /**
     * @return list<string>
     */
    private function configuredTokens(): array
    {
        $tokens = [];
        $token = config('telegram-bot.token');

        if (is_string($token)) {
            $tokens[] = $token;
        }

        $bots = config('telegram-bot.bots', []);

        if (is_array($bots)) {
            foreach ($bots as $bot) {
                if (is_array($bot) && is_string($bot['token'] ?? null)) {
                    $tokens[] = $bot['token'];
                }
            }
        }

        return $tokens;
    }
}
