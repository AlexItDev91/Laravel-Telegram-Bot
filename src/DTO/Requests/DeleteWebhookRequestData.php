<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `deleteWebhook`.
 */
final readonly class DeleteWebhookRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'deleteWebhook';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        ?bool $dropPendingUpdates = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'drop_pending_updates' => $dropPendingUpdates,
        ], $extra)));
    }
}
