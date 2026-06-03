<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodSchema;
use InvalidArgumentException;

/**
 * @phpstan-consistent-constructor
 */
abstract readonly class TelegramBotApiRequestData extends TelegramBotRequestData implements TelegramBotMethodRequest
{
    public const METHOD = '';

    /**
     * @param  array<string, mixed>  $parameters
     */
    final public function __construct(array $parameters = [], private bool $validateRequiredParameters = true)
    {
        if ($this->validateRequiredParameters) {
            $this->assertRequiredParameters($parameters);
        }

        parent::__construct($parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function fromParameters(array $parameters = [], bool $validateRequiredParameters = true): static
    {
        return new static($parameters, $validateRequiredParameters);
    }

    public function withoutRequiredValidation(): static
    {
        return new static($this->parameters, false);
    }

    public function with(string $parameter, mixed $value): static
    {
        return new static(array_merge($this->parameters, [$parameter => $value]), $this->validatesRequiredParameters());
    }

    public function method(): string
    {
        return static::METHOD;
    }

    public function validatesRequiredParameters(): bool
    {
        return $this->validateRequiredParameters;
    }

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    public function schema(): array
    {
        return TelegramBotApiMethodSchema::parameters($this->method());
    }

    /**
     * @return list<string>
     */
    public function requiredParameters(): array
    {
        return TelegramBotApiMethodSchema::requiredParameters($this->method());
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function assertRequiredParameters(array $parameters): void
    {
        $missing = array_values(array_filter(
            $this->requiredParameters(),
            static fn (string $parameter): bool => ! array_key_exists($parameter, $parameters) || $parameters[$parameter] === null,
        ));

        if ($missing === []) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'Telegram Bot API method [%s] requires parameter(s): %s.',
            $this->method(),
            implode(', ', $missing),
        ));
    }
}
