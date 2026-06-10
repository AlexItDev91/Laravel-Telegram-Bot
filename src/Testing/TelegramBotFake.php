<?php

namespace AlexItDev91\LaravelTelegramBot\Testing;

use Override;
use AlexItDev91\LaravelTelegramBot\Concerns\SendsTelegramMessageShortcuts;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotChannelNotConfiguredException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotConfigurationException;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
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
    use SendsTelegramMessageShortcuts;

    /**
     * @var list<array{bot: string, channel: string|null, method: string, parameters: array<string, mixed>}>
     */
    private array $calls = [];

    /**
     * @var array<string, list<mixed>>
     */
    private array $results = [];

    private ?string $selectedBot = null;

    /**
     * @var array<string, string>
     */
    private array $dynamicBotAliases = [];

    private int $dynamicBotSequence = 0;

    #[Override]
    public function bot(?string $name = null): TelegramBotClient
    {
        $this->selectedBot = $name ?? 'default';

        return $this;
    }

    public function botToken(string $token, ?string $apiUrl = null, ?float $timeout = null): TelegramBotClient
    {
        $this->selectedBot = $this->dynamicBotAlias($token);

        return $this;
    }

    #[Override]
    public function channel(
        string $name,
        ?string $bot = null,
        ?string $token = null,
        ?string $apiUrl = null,
        ?float $timeout = null,
    ): TelegramBotChannel
    {
        $channels = config('telegram-bot.channels', []);

        if (! is_array($channels) || ! is_array($channels[$name] ?? null)) {
            throw new TelegramBotChannelNotConfiguredException("Telegram Bot channel [$name] is not configured.");
        }

        $config = TelegramChannelConfigData::fromArray($channels[$name]);
        if ($bot !== null && trim($bot) !== '' && $token !== null && trim($token) !== '') {
            throw new InvalidArgumentException('Use either a configured Telegram bot name or a dynamic bot token, not both.');
        }

        $botName = $token !== null && trim($token) !== ''
            ? $this->dynamicBotAlias($token)
            : ($bot !== null && trim($bot) !== '' ? $bot : ($config->bot ?? 'default'));

        return new TelegramBotFakeChannel(
            fake: $this,
            channel: $name,
            bot: $botName,
            config: $config,
        );
    }

    public function to(
        string|int $chatId,
        ?string $bot = null,
        ?string $token = null,
        string|int|null $messageThreadId = null,
        string|int|null $directMessagesTopicId = null,
        ?string $apiUrl = null,
        ?float $timeout = null,
    ): TelegramBotChannel {
        if ($bot !== null && trim($bot) !== '' && $token !== null && trim($token) !== '') {
            throw new InvalidArgumentException('Use either a configured Telegram bot name or a dynamic bot token, not both.');
        }

        return new TelegramBotFakeChannel(
            fake: $this,
            channel: null,
            bot: $token !== null && trim($token) !== '' ? $this->dynamicBotAlias($token) : ($bot ?? 'default'),
            config: new TelegramChannelConfigData(
                bot: $bot,
                chatId: $chatId,
                messageThreadId: $messageThreadId,
                directMessagesTopicId: $directMessagesTopicId,
            ),
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    #[Override]
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

    public function send(TelegramMessage $message): mixed
    {
        if (! $message->hasChatId()) {
            throw new InvalidArgumentException('Telegram fluent messages sent through a bot fake must define a chat_id with to().');
        }

        return $this->call($message->method(), $message->payload());
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
            'parameters' => $this->normalizeParameters($parameters),
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
            is_array($expected) => $this->payloadMatcher($this->stringKeyedPayload($expected)),
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

    /**
     * @param  callable(array<string, mixed>, string): bool|null  $callback
     */
    public function assertCalledUsingToken(
        string $token,
        string|TelegramBotApiMethod $method,
        ?callable $callback = null,
        ?int $times = null,
    ): void {
        $botAlias = $this->dynamicBotAlias($token);
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        $matching = array_values(array_filter(
            $this->calls,
            static fn (array $call): bool => $call['method'] === $methodName
                && $call['bot'] === $botAlias
                && ($callback === null || $callback($call['parameters'], $call['bot']) === true),
        ));

        if ($times !== null) {
            Assert::assertCount($times, $matching, "Expected Telegram Bot token-authenticated method [$methodName] to be called $times times.");

            return;
        }

        Assert::assertNotSame([], $matching, "Expected Telegram Bot token-authenticated method [$methodName] to be called.");
    }

    /**
     * @param  callable(array<string, mixed>, string): bool|null  $callback
     */
    public function assertSentMessageUsingToken(string $token, ?callable $callback = null, ?int $times = null): void
    {
        $this->assertCalledUsingToken($token, TelegramBotApiMethod::sendMessage, $callback, $times);
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
        $expected = $this->normalizeParameters($expected);

        return static fn (array $parameters): bool => self::arrayContainsSubset($expected, $parameters);
    }

    /**
     * @param  array<int|string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function stringKeyedPayload(array $payload): array
    {
        $stringKeyed = [];

        foreach ($payload as $key => $value) {
            if (! is_string($key)) {
                throw new InvalidArgumentException('Expected Telegram Bot fake payload keys to be strings.');
            }

            $stringKeyed[$key] = $value;
        }

        return $stringKeyed;
    }

    /**
     * @param  array<int|string, mixed>  $expected
     * @param  array<int|string, mixed>  $actual
     */
    private static function arrayContainsSubset(array $expected, array $actual): bool
    {
        foreach ($expected as $key => $expectedValue) {
            if (! array_key_exists($key, $actual)) {
                return false;
            }

            $actualValue = $actual[$key];

            if (is_array($expectedValue)) {
                if (! is_array($actualValue) || ! self::arrayContainsSubset($expectedValue, $actualValue)) {
                    return false;
                }

                continue;
            }

            if ($actualValue !== $expectedValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return array<string, mixed>
     */
    private function normalizeParameters(array|TelegramBotRequestData $parameters): array
    {
        return $parameters instanceof TelegramBotRequestData
            ? $parameters->toArray()
            : TelegramBotRequestData::fromArray($parameters)->toArray();
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

    private function dynamicBotAlias(string $token): string
    {
        if (trim($token) === '') {
            throw new TelegramBotConfigurationException('Telegram Bot token is not configured.');
        }

        return $this->dynamicBotAliases[$token] ??= 'dynamic-token-'.(++$this->dynamicBotSequence);
    }
}
