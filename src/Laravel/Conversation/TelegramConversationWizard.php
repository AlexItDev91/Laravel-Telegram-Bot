<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Conversation;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramConversationData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use InvalidArgumentException;

final class TelegramConversationWizard
{
    private const string HISTORY = '_wizard_history';

    /**
     * @var array<string, TelegramConversationStep>
     */
    private array $steps = [];

    /**
     * @var list<string>
     */
    private array $cancelCommands = ['/cancel'];

    /**
     * @var list<string>
     */
    private array $backCommands = ['/back'];

    private ?string $firstStep = null;

    private ?int $ttl = null;

    private ?int $timeoutSeconds = null;

    private ?string $cancelledMessage = null;

    private ?string $expiredMessage = null;

    private function __construct(private readonly TelegramConversationWorkflow $workflow)
    {
        //
    }

    public static function for(TelegramConversationWorkflow $workflow): self
    {
        return new self($workflow);
    }

    public static function make(TelegramConversationWorkflow $workflow): self
    {
        return self::for($workflow);
    }

    public function ttl(?int $seconds): self
    {
        $this->ttl = $seconds;

        return $this;
    }

    public function timeout(?int $seconds): self
    {
        $this->timeoutSeconds = $seconds;

        return $this;
    }

    public function cancelOn(string ...$commands): self
    {
        $this->cancelCommands = $this->normalizeCommands($commands);

        return $this;
    }

    public function backOn(string ...$commands): self
    {
        $this->backCommands = $this->normalizeCommands($commands);

        return $this;
    }

    public function cancelledMessage(?string $message): self
    {
        $this->cancelledMessage = $message;

        return $this;
    }

    public function expiredMessage(?string $message): self
    {
        $this->expiredMessage = $message;

        return $this;
    }

    public function addStep(TelegramConversationStep $step): self
    {
        $this->steps[$step->name()] = $step;
        $this->firstStep ??= $step->name();

        return $this;
    }

    public function step(TelegramConversationStep|string $step, ?string $storeAs = null): TelegramConversationStep
    {
        $conversationStep = is_string($step)
            ? TelegramConversationStep::make($step)
            : $step;

        if ($storeAs !== null) {
            $conversationStep->storeAs($storeAs);
        }

        $this->addStep($conversationStep);

        return $conversationStep;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function start(array $data = []): TelegramConversationWizardResult
    {
        $step = $this->requireStep($this->requireFirstStep());
        $conversation = $this->workflow->start(
            state: $step->name(),
            data: array_merge($data, [self::HISTORY => []]),
            ttl: $this->ttl,
            timeoutSeconds: $this->timeoutSeconds,
        );

        return $this->result(
            status: TelegramConversationWizardResult::STATUS_STARTED,
            step: $step->name(),
            previousStep: null,
            message: $step->promptMessage(),
            context: TelegramConversationContext::fromConversation($conversation),
        );
    }

    public function handle(TelegramWebhookUpdate $update): TelegramConversationWizardResult
    {
        if ($this->workflow->isExpired()) {
            $context = $this->workflow->context();
            $previousStep = $this->workflow->state();
            $this->workflow->reset();

            return $this->result(
                status: TelegramConversationWizardResult::STATUS_EXPIRED,
                step: null,
                previousStep: $previousStep,
                message: $this->expiredMessage,
                context: $context,
            );
        }

        $current = $this->workflow->current();

        if ($current === null) {
            return $this->start();
        }

        $context = TelegramConversationContext::fromConversation($current);
        $step = $this->steps[$current->state()] ?? null;
        $value = $this->resolveInput($update, $context, $step);

        if ($this->matches($value, $this->cancelCommands)) {
            $this->workflow->reset();

            return $this->result(
                status: TelegramConversationWizardResult::STATUS_CANCELLED,
                step: null,
                previousStep: $current->state(),
                message: $this->cancelledMessage,
                context: $context,
                value: $value,
            );
        }

        if ($this->matches($value, $this->backCommands)) {
            return $this->back($current, $context, $value);
        }

        if ($step === null) {
            return $this->result(
                status: TelegramConversationWizardResult::STATUS_IGNORED,
                step: $current->state(),
                previousStep: null,
                message: null,
                context: $context,
                value: $value,
            );
        }

        if (! $step->allows($value, $context, $update)) {
            return $this->result(
                status: TelegramConversationWizardResult::STATUS_INVALID,
                step: $step->name(),
                previousStep: null,
                message: $step->invalidMessage() ?? $step->promptMessage(),
                context: $context,
                value: $value,
            );
        }

        $storedData = $this->storedData($step, $value);
        $updatedContext = $context->merge($storedData);
        $nextStep = $step->nextStep($value, $updatedContext, $update);

        if ($nextStep === null) {
            $this->workflow->reset();

            return $this->result(
                status: TelegramConversationWizardResult::STATUS_COMPLETED,
                step: null,
                previousStep: $step->name(),
                message: $step->completionMessage(),
                context: $updatedContext,
                value: $value,
            );
        }

        $next = $this->requireStep($nextStep);
        $conversation = $this->workflow->advance(
            state: $next->name(),
            data: array_merge($storedData, [
                self::HISTORY => [...$this->history($context), $step->name()],
            ]),
            ttl: $this->ttl,
        );

        return $this->result(
            status: TelegramConversationWizardResult::STATUS_ADVANCED,
            step: $next->name(),
            previousStep: $step->name(),
            message: $next->promptMessage(),
            context: TelegramConversationContext::fromConversation($conversation),
            value: $value,
        );
    }

    private function back(
        TelegramConversationData $current,
        TelegramConversationContext $context,
        mixed $value,
    ): TelegramConversationWizardResult {
        $history = $this->history($context);
        $previous = array_pop($history);

        if ($previous === null) {
            $step = $this->steps[$current->state()] ?? null;

            return $this->result(
                status: TelegramConversationWizardResult::STATUS_BACK,
                step: $current->state(),
                previousStep: null,
                message: $step?->promptMessage(),
                context: $context,
                value: $value,
            );
        }

        $step = $this->requireStep($previous);
        $conversation = $this->workflow->advance(
            state: $step->name(),
            data: [self::HISTORY => $history],
            ttl: $this->ttl,
        );

        return $this->result(
            status: TelegramConversationWizardResult::STATUS_BACK,
            step: $step->name(),
            previousStep: $current->state(),
            message: $step->promptMessage(),
            context: TelegramConversationContext::fromConversation($conversation),
            value: $value,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withoutInternalData(array $data): array
    {
        unset($data[self::HISTORY]);

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function storedData(TelegramConversationStep $step, mixed $value): array
    {
        $key = $step->storageKey();

        return $key === null ? [] : [$key => $value];
    }

    /**
     * @return list<string>
     */
    private function history(TelegramConversationContext $context): array
    {
        $history = $context->array(self::HISTORY) ?? [];

        return array_values(array_filter($history, is_string(...)));
    }

    private function resolveInput(
        TelegramWebhookUpdate $update,
        TelegramConversationContext $context,
        ?TelegramConversationStep $step,
    ): mixed {
        if ($step !== null) {
            return $step->resolveInput($update, $context);
        }

        $callbackData = $update->callbackQuery()?->data();

        if ($callbackData !== null) {
            return trim($callbackData);
        }

        $message = $update->effectiveMessage();
        $value = $message?->text() ?? $message?->caption();

        return is_string($value) ? trim($value) : $value;
    }

    /**
     * @param  list<string>  $commands
     */
    private function matches(mixed $value, array $commands): bool
    {
        return is_string($value) && in_array(trim($value), $commands, true);
    }

    private function requireFirstStep(): string
    {
        return $this->firstStep
            ?? throw new InvalidArgumentException('A Telegram conversation wizard must define at least one step.');
    }

    private function requireStep(string $name): TelegramConversationStep
    {
        return $this->steps[$name]
            ?? throw new InvalidArgumentException(sprintf('Telegram conversation wizard step [%s] is not defined.', $name));
    }

    /**
     * @param  array<int|string, string>  $commands
     * @return list<string>
     */
    private function normalizeCommands(array $commands): array
    {
        return array_values(array_filter(
            array_map(static fn (string $command): string => trim($command), $commands),
            static fn (string $command): bool => $command !== '',
        ));
    }

    private function result(
        string $status,
        ?string $step,
        ?string $previousStep,
        ?string $message,
        TelegramConversationContext $context,
        mixed $value = null,
    ): TelegramConversationWizardResult {
        return new TelegramConversationWizardResult(
            status: $status,
            step: $step,
            previousStep: $previousStep,
            message: $message,
            context: new TelegramConversationContext($this->withoutInternalData($context->toArray())),
            value: $value,
        );
    }
}
