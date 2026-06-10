<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Conversation;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Closure;

final class TelegramConversationStep
{
    /**
     * @var (Closure(TelegramWebhookUpdate, TelegramConversationContext): mixed)|null
     */
    private ?Closure $inputResolver = null;

    /**
     * @var (Closure(mixed, TelegramConversationContext, TelegramWebhookUpdate): bool)|null
     */
    private ?Closure $validator = null;

    /**
     * @var (Closure(mixed, TelegramConversationContext, TelegramWebhookUpdate): string|null)|null
     */
    private ?Closure $nextResolver = null;

    private ?string $prompt = null;

    private ?string $invalidMessage = null;

    private ?string $storeAs = null;

    private ?string $completeMessage = null;

    private bool $completes = false;

    private function __construct(private readonly string $name)
    {
        //
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public static function text(string $name, string $storeAs): self
    {
        return self::make($name)->storeAs($storeAs);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function prompt(string $message): self
    {
        $this->prompt = $message;

        return $this;
    }

    public function invalid(string $message): self
    {
        $this->invalidMessage = $message;

        return $this;
    }

    public function storeAs(string $key): self
    {
        $this->storeAs = $key;

        return $this;
    }

    /**
     * @param  callable(TelegramWebhookUpdate, TelegramConversationContext): mixed  $resolver
     */
    public function inputUsing(callable $resolver): self
    {
        $this->inputResolver = Closure::fromCallable($resolver);

        return $this;
    }

    /**
     * @param  callable(mixed, TelegramConversationContext, TelegramWebhookUpdate): bool  $validator
     */
    public function validate(callable $validator): self
    {
        $this->validator = Closure::fromCallable($validator);

        return $this;
    }

    public function next(callable|string $step): self
    {
        $this->nextResolver = is_string($step)
            ? static fn (): string => $step
            : Closure::fromCallable($step);
        $this->completes = false;

        return $this;
    }

    public function complete(?string $message = null): self
    {
        $this->completes = true;
        $this->completeMessage = $message;
        $this->nextResolver = null;

        return $this;
    }

    public function promptMessage(): ?string
    {
        return $this->prompt;
    }

    public function invalidMessage(): ?string
    {
        return $this->invalidMessage;
    }

    public function completionMessage(): ?string
    {
        return $this->completeMessage;
    }

    public function storageKey(): ?string
    {
        return $this->storeAs;
    }

    public function resolveInput(TelegramWebhookUpdate $update, TelegramConversationContext $context): mixed
    {
        if ($this->inputResolver !== null) {
            return ($this->inputResolver)($update, $context);
        }

        $callbackData = $update->callbackQuery()?->data();

        if ($callbackData !== null) {
            return trim($callbackData);
        }

        $message = $update->effectiveMessage();
        $value = $message?->text() ?? $message?->caption();

        return is_string($value) ? trim($value) : $value;
    }

    public function allows(mixed $value, TelegramConversationContext $context, TelegramWebhookUpdate $update): bool
    {
        return $this->validator === null || ($this->validator)($value, $context, $update);
    }

    public function nextStep(mixed $value, TelegramConversationContext $context, TelegramWebhookUpdate $update): ?string
    {
        if ($this->completes || $this->nextResolver === null) {
            return null;
        }

        $next = ($this->nextResolver)($value, $context, $update);

        return is_string($next) && $next !== '' ? $next : null;
    }
}
