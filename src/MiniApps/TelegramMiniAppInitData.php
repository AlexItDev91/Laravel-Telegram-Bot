<?php

namespace AlexItDev91\LaravelTelegramBot\MiniApps;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use JsonException;

final readonly class TelegramMiniAppInitData implements TelegramBotData
{
    /**
     * @param  array<string, string>  $fields
     * @param  array<string, mixed>  $payload
     */
    private function __construct(
        private string $raw,
        private array $fields,
        private array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, string>  $fields
     */
    public static function fromFields(string $raw, array $fields): self
    {
        return new self($raw, $fields, self::decodePayload($fields));
    }

    public function raw(): string
    {
        return $this->raw;
    }

    /**
     * @return array<string, string>
     */
    public function fields(): array
    {
        return $this->fields;
    }

    public function hash(): ?string
    {
        return $this->stringAt('hash');
    }

    public function signature(): ?string
    {
        return $this->stringAt('signature');
    }

    public function queryId(): ?string
    {
        return $this->stringAt('query_id');
    }

    public function user(): ?TelegramMiniAppUserData
    {
        $payload = $this->objectAt('user');

        return $payload !== null ? TelegramMiniAppUserData::fromPayload($payload) : null;
    }

    public function receiver(): ?TelegramMiniAppUserData
    {
        $payload = $this->objectAt('receiver');

        return $payload !== null ? TelegramMiniAppUserData::fromPayload($payload) : null;
    }

    public function chat(): ?TelegramMiniAppChatData
    {
        $payload = $this->objectAt('chat');

        return $payload !== null ? TelegramMiniAppChatData::fromPayload($payload) : null;
    }

    public function chatType(): ?string
    {
        return $this->stringAt('chat_type');
    }

    public function chatInstance(): ?string
    {
        return $this->stringAt('chat_instance');
    }

    public function startParam(): ?string
    {
        return $this->stringAt('start_param');
    }

    public function canSendAfter(): ?int
    {
        return $this->intAt('can_send_after');
    }

    public function authDate(): ?int
    {
        return $this->intAt('auth_date');
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @param  array<string, string>  $fields
     * @return array<string, mixed>
     */
    private static function decodePayload(array $fields): array
    {
        $payload = [];

        foreach ($fields as $key => $value) {
            $payload[$key] = match ($key) {
                'user', 'receiver', 'chat' => self::decodeJsonObject($value) ?? $value,
                'auth_date', 'can_send_after' => self::intFromString($value) ?? $value,
                default => $value,
            };
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function decodeJsonObject(string $value): ?array
    {
        try {
            $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);
        } catch (JsonException) {
            return null;
        }

        if (! is_array($decoded) || array_is_list($decoded)) {
            return null;
        }

        $object = [];

        foreach ($decoded as $key => $nestedValue) {
            if (! is_string($key)) {
                return null;
            }

            $object[$key] = $nestedValue;
        }

        return $object;
    }

    private static function intFromString(string $value): ?int
    {
        return ctype_digit($value) ? (int) $value : null;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function objectAt(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) && ! array_is_list($value) ? $value : null;
    }
}
