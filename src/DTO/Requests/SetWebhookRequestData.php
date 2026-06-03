<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\InputFile;

/**
 * Generated typed request builder for Telegram Bot API method `setWebhook`.
 */
final readonly class SetWebhookRequestData extends TelegramBotApiRequestData
{
    public const METHOD = 'setWebhook';

    /**
     * @param  array<string|int, mixed>|null  $allowedUpdates
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $url,
        InputFile|null $certificate = null,
        ?string $ipAddress = null,
        ?int $maxConnections = null,
        ?array $allowedUpdates = null,
        ?bool $dropPendingUpdates = null,
        ?string $secretToken = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'url' => $url,
            'certificate' => $certificate,
            'ip_address' => $ipAddress,
            'max_connections' => $maxConnections,
            'allowed_updates' => $allowedUpdates,
            'drop_pending_updates' => $dropPendingUpdates,
            'secret_token' => $secretToken,
        ], $extra)));
    }
}
