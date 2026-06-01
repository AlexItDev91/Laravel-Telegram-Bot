<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Passport;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class PassportScopeElement implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, string>|null  $oneOf
     * @param  array<string, mixed>  $extra
     */
    private function __construct(
        private ?string $type = null,
        private ?array $oneOf = null,
        private ?bool $selfie = null,
        private ?bool $translation = null,
        private ?bool $nativeNames = null,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function one(
        string $type,
        ?bool $selfie = null,
        ?bool $translation = null,
        ?bool $nativeNames = null,
        array $extra = [],
    ): self {
        return new self($type, null, $selfie, $translation, $nativeNames, $extra);
    }

    /**
     * @param  array<int, string>  $types
     * @param  array<string, mixed>  $extra
     */
    public static function oneOfSeveral(
        array $types,
        ?bool $selfie = null,
        ?bool $translation = null,
        ?bool $nativeNames = null,
        array $extra = [],
    ): self {
        return new self(null, $types, $selfie, $translation, $nativeNames, $extra);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return self::payload([
            'type' => $this->type,
            'one_of' => $this->oneOf,
            'selfie' => $this->selfie,
            'translation' => $this->translation,
            'native_names' => $this->nativeNames,
        ], $this->extra);
    }
}
