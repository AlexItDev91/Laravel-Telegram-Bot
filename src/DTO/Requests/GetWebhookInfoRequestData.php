<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `getWebhookInfo`.
 */
final readonly class GetWebhookInfoRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'getWebhookInfo';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([], $extra)));
    }
}
