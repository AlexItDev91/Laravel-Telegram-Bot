<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Concerns;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use InvalidArgumentException;

trait BuildsTelegramBotPayload
{
    /**
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $extra
     * @param  list<string>  $required
     * @return array<string, mixed>
     */
    private static function payload(array $parameters, array $extra = [], array $required = []): array
    {
        self::assertNoDuplicateExtraFields($parameters, $extra);

        $parameters = array_merge($parameters, $extra);

        self::assertRequiredPayloadFields($parameters, $required);

        return TelegramBotRequestData::normalizeValue(
            TelegramBotRequestData::withoutNullValues($parameters),
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $extra
     */
    private static function assertNoDuplicateExtraFields(array $parameters, array $extra): void
    {
        $duplicates = array_values(array_intersect(array_keys($parameters), array_keys($extra)));

        if ($duplicates !== []) {
            throw new InvalidArgumentException(sprintf(
                'Telegram Bot payload extra fields must not duplicate typed fields: [%s].',
                implode(', ', $duplicates),
            ));
        }
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @param  list<string>  $required
     */
    private static function assertRequiredPayloadFields(array $parameters, array $required): void
    {
        foreach ($required as $field) {
            if (! array_key_exists($field, $parameters) || self::isBlankPayloadValue($parameters[$field])) {
                throw new InvalidArgumentException("Telegram Bot payload field [{$field}] must not be empty.");
            }
        }
    }

    private static function assertPositiveInteger(string $field, int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("Telegram Bot payload field [{$field}] must be greater than zero.");
        }
    }

    private static function assertNonNegativeInteger(string $field, int $value): void
    {
        if ($value < 0) {
            throw new InvalidArgumentException("Telegram Bot payload field [{$field}] must not be negative.");
        }
    }

    private static function assertGameMessageReference(int|string|null $chatId, ?int $messageId, ?string $inlineMessageId): void
    {
        self::assertMessageReference($chatId, $messageId, $inlineMessageId);
    }

    private static function assertMessageReference(int|string|null $chatId, ?int $messageId, ?string $inlineMessageId): void
    {
        if (! self::isBlankPayloadValue($inlineMessageId)) {
            return;
        }

        if ($messageId !== null && ! self::isBlankPayloadValue($chatId)) {
            return;
        }

        throw new InvalidArgumentException('Telegram Bot payload requires either [inline_message_id] or both [chat_id] and [message_id].');
    }

    private static function isBlankPayloadValue(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_array($value)) {
            return $value === [];
        }

        return false;
    }
}
