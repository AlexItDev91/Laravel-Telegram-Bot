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
            $this->assertMethodSpecificParameters($parameters);
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
            fn (string $parameter): bool => ! array_key_exists($parameter, $parameters) || $this->isBlankParameterValue($parameters[$parameter]),
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

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function assertMethodSpecificParameters(array $parameters): void
    {
        if ($this->requiresMessageReference()) {
            $this->assertMessageReference($parameters);
        }

        if ($this->methodName === 'sendRichMessageDraft') {
            $draftId = $parameters['draft_id'] ?? null;

            if ($draftId === 0) {
                throw new InvalidArgumentException('Telegram Bot API method [sendRichMessageDraft] requires parameter [draft_id] to be non-zero.');
            }
        }
    }

    private function requiresMessageReference(): bool
    {
        return in_array($this->methodName, [
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
            $this->methodName,
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
