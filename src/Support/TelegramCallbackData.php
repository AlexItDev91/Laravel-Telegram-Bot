<?php

namespace AlexItDev91\LaravelTelegramBot\Support;

use InvalidArgumentException;
use Stringable;

final readonly class TelegramCallbackData implements Stringable
{
    public const int MAX_BYTES = 64;

    /**
     * @param  array<string, int|string|bool>  $parameters
     */
    private function __construct(
        private string $action,
        private array $parameters = [],
    ) {
        self::assertAction($action);
        self::assertParameters($parameters);
        self::assertLength($this->build());
    }

    /**
     * @param  array<string, int|string|bool>  $parameters
     */
    public static function action(string $action, array $parameters = []): self
    {
        return new self($action, $parameters);
    }

    /**
     * @param  array<string, int|string|bool>  $parameters
     */
    public static function make(string $action, array $parameters = []): self
    {
        return self::action($action, $parameters);
    }

    public static function parse(string $data): self
    {
        self::assertLength($data);

        [$action, $query] = array_pad(explode('?', $data, 2), 2, '');
        parse_str($query, $parsed);

        $parameters = [];
        foreach ($parsed as $key => $value) {
            if (! is_string($key) || is_array($value)) {
                throw new InvalidArgumentException('Telegram callback data parameters must be scalar values.');
            }

            $parameters[$key] = (string) $value;
        }

        return new self($action, $parameters);
    }

    public function with(string $key, int|string|bool|null $value): self
    {
        self::assertParameterKey($key);

        $parameters = $this->parameters;

        if ($value === null) {
            unset($parameters[$key]);

            return new self($this->action, $parameters);
        }

        $parameters[$key] = $value;

        return new self($this->action, $parameters);
    }

    public function actionName(): string
    {
        return $this->action;
    }

    public function matches(string $action): bool
    {
        return $this->action === $action;
    }

    public function parameter(string $key): ?string
    {
        $value = $this->parameters[$key] ?? null;

        return $value !== null ? (string) $value : null;
    }

    /**
     * @return array<string, int|string|bool>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function toString(): string
    {
        return $this->build();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private function build(): string
    {
        if ($this->parameters === []) {
            return $this->action;
        }

        return $this->action.'?'.http_build_query($this->parameters, '', '&', PHP_QUERY_RFC3986);
    }

    private static function assertAction(string $action): void
    {
        if (trim($action) === '') {
            throw new InvalidArgumentException('Telegram callback data action must not be empty.');
        }

        if (str_contains($action, '?') || str_contains($action, '&') || str_contains($action, '=')) {
            throw new InvalidArgumentException('Telegram callback data action must not contain query delimiters.');
        }
    }

    /**
     * @param  array<string, int|string|bool>  $parameters
     */
    private static function assertParameters(array $parameters): void
    {
        foreach ($parameters as $key => $value) {
            self::assertParameterKey($key);

            if (! is_int($value) && ! is_string($value) && ! is_bool($value)) {
                throw new InvalidArgumentException('Telegram callback data parameters must be int, string, or bool values.');
            }
        }
    }

    private static function assertParameterKey(string $key): void
    {
        if (trim($key) === '') {
            throw new InvalidArgumentException('Telegram callback data parameter keys must not be empty.');
        }
    }

    private static function assertLength(string $data): void
    {
        $bytes = strlen($data);

        if ($bytes < 1 || $bytes > self::MAX_BYTES) {
            throw new InvalidArgumentException('Telegram callback data must be between 1 and 64 bytes.');
        }
    }
}
