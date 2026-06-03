<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodSchema;
use InvalidArgumentException;

readonly class TelegramBotMethodRequestData extends TelegramBotRequestData implements TelegramBotMethodRequest
{
    private string $methodName;

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function __construct(
        string|TelegramBotApiMethod $method,
        array $parameters = [],
        private bool $validateRequiredParameters = true,
    ) {
        $this->methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;

        if (! TelegramBotApiMethodSchema::supports($this->methodName)) {
            throw new InvalidArgumentException("Telegram Bot API method [$this->methodName] does not have a generated request schema.");
        }

        if ($this->validateRequiredParameters) {
            $this->assertRequiredParameters($parameters);
        }

        parent::__construct($parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function forMethod(
        string|TelegramBotApiMethod $method,
        array $parameters = [],
        bool $validateRequiredParameters = true,
    ): self {
        return new self($method, $parameters, $validateRequiredParameters);
    }

    #[Override]
    public function method(): string
    {
        return $this->methodName;
    }

    #[Override]
    public function validatesRequiredParameters(): bool
    {
        return $this->validateRequiredParameters;
    }

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    #[Override]
    public function schema(): array
    {
        return TelegramBotApiMethodSchema::parameters($this->methodName);
    }

    /**
     * @return list<string>
     */
    #[Override]
    public function requiredParameters(): array
    {
        return TelegramBotApiMethodSchema::requiredParameters($this->methodName);
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
            $this->methodName,
            implode(', ', $missing),
        ));
    }
}
