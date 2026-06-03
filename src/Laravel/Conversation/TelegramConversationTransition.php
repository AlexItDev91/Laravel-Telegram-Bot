<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Conversation;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Closure;

final readonly class TelegramConversationTransition
{
    /**
     * @param  Closure(TelegramConversationContext, TelegramWebhookUpdate|null): bool|null  $guard
     */
    public function __construct(
        public string $from,
        public string $to,
        private ?Closure $guard = null,
        public ?int $ttl = null,
    ) {
        //
    }

    /**
     * @param  callable(TelegramConversationContext, TelegramWebhookUpdate|null): bool  $guard
     */
    public static function guarded(string $from, string $to, callable $guard, ?int $ttl = null): self
    {
        return new self($from, $to, $guard(...), $ttl);
    }

    public static function from(string $from, string $to, ?int $ttl = null): self
    {
        return new self($from, $to, null, $ttl);
    }

    public function allows(TelegramConversationContext $context, ?TelegramWebhookUpdate $update = null): bool
    {
        return $this->guard === null || ($this->guard)($context, $update) === true;
    }
}
