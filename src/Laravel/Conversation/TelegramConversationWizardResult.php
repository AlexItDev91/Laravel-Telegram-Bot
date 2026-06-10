<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Conversation;

final readonly class TelegramConversationWizardResult
{
    public const string STATUS_STARTED = 'started';

    public const string STATUS_ADVANCED = 'advanced';

    public const string STATUS_INVALID = 'invalid';

    public const string STATUS_COMPLETED = 'completed';

    public const string STATUS_CANCELLED = 'cancelled';

    public const string STATUS_BACK = 'back';

    public const string STATUS_EXPIRED = 'expired';

    public const string STATUS_IGNORED = 'ignored';

    public function __construct(
        private string $status,
        private ?string $step,
        private ?string $previousStep,
        private ?string $message,
        private TelegramConversationContext $context,
        private mixed $value = null,
    ) {
        //
    }

    public function status(): string
    {
        return $this->status;
    }

    public function step(): ?string
    {
        return $this->step;
    }

    public function previousStep(): ?string
    {
        return $this->previousStep;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function context(): TelegramConversationContext
    {
        return $this->context;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function hasMessage(): bool
    {
        return $this->message !== null && $this->message !== '';
    }

    public function completed(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function cancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function invalid(): bool
    {
        return $this->status === self::STATUS_INVALID;
    }

    /**
     * @return array{status: string, step: string|null, previous_step: string|null, message: string|null, context: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'step' => $this->step,
            'previous_step' => $this->previousStep,
            'message' => $this->message,
            'context' => $this->context->toArray(),
        ];
    }
}
