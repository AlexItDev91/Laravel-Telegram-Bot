<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodSchema;
use InvalidArgumentException;
use Override;

/**
 * @phpstan-consistent-constructor
 */
abstract readonly class TelegramBotApiRequestData extends TelegramBotRequestData implements TelegramBotMethodRequest
{
    public const string METHOD = '';

    /**
     * @param  array<string, mixed>  $parameters
     */
    final public function __construct(array $parameters = [], private bool $validateRequiredParameters = true)
    {
        if ($this->validateRequiredParameters) {
            $this->assertRequiredParameters($parameters);
            $this->assertMethodSpecificParameters($parameters);
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

    #[Override]
    public function method(): string
    {
        return static::METHOD;
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
        return TelegramBotApiMethodSchema::parameters($this->method());
    }

    /**
     * @return list<string>
     */
    #[Override]
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
            fn (string $parameter): bool => ! array_key_exists($parameter, $parameters) || $this->isBlankParameterValue($parameters[$parameter]),
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

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function assertMethodSpecificParameters(array $parameters): void
    {
        if ($this->requiresMessageReference()) {
            $this->assertMessageReference($parameters);
        }

        if ($this->method() === 'sendRichMessageDraft') {
            $draftId = $parameters['draft_id'] ?? null;

            if ($draftId === 0) {
                throw new InvalidArgumentException('Telegram Bot API method [sendRichMessageDraft] requires parameter [draft_id] to be non-zero.');
            }
        }
    }

    private function requiresMessageReference(): bool
    {
        return in_array($this->method(), [
            'editMessageCaption',
            'editMessageChecklist',
            'editMessageLiveLocation',
            'editMessageMedia',
            'editMessageReplyMarkup',
            'editMessageText',
            'getGameHighScores',
            'setGameScore',
            'stopMessageLiveLocation',
            'stopPoll',
        ], true);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function assertMessageReference(array $parameters): void
    {
        if (! $this->isBlankParameterValue($parameters['inline_message_id'] ?? null)) {
            return;
        }

        if (! $this->isBlankParameterValue($parameters['chat_id'] ?? null) && ! $this->isBlankParameterValue($parameters['message_id'] ?? null)) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'Telegram Bot API method [%s] requires either [inline_message_id] or both [chat_id] and [message_id].',
            $this->method(),
        ));
    }

    private function isBlankParameterValue(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if ($value instanceof TelegramBotRequestData) {
            return $value->toArray() === [];
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        return is_array($value) && $value === [];
    }
}
