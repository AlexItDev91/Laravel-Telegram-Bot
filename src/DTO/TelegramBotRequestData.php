<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\InputFile;
use BackedEnum;

readonly class TelegramBotRequestData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public function __construct(public array $parameters = [])
    {
        //
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function fromArray(array $parameters): self
    {
        return new self($parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function forMethod(
        string|TelegramBotApiMethod $method,
        array $parameters = [],
        bool $validateRequiredParameters = true,
    ): TelegramBotMethodRequestData {
        return TelegramBotMethodRequestData::forMethod($method, $parameters, $validateRequiredParameters);
    }

    public function containsFiles(): bool
    {
        return $this->containsInputFile($this->parameters);
    }

    /**
     * @return array<string, mixed>
     */
    public function json(): array
    {
        return self::normalizeValue($this->parameters);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->json();
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return array<string, mixed>
     */
    public static function withoutNullValues(array $parameters): array
    {
        return array_filter($parameters, static fn (mixed $value): bool => $value !== null);
    }

    public static function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if ($value instanceof InputFile) {
            return $value;
        }

        if ($value instanceof TelegramBotData) {
            return $value->toArray();
        }

        if (! is_array($value)) {
            return $value;
        }

        $normalized = [];

        foreach ($value as $key => $nestedValue) {
            $normalized[$key] = self::normalizeValue($nestedValue);
        }

        return $normalized;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function multipart(): array
    {
        $parts = [];
        $fileParts = [];
        $attachmentIndex = 0;
        $reservedNames = array_map('strval', array_keys($this->parameters));

        foreach ($this->parameters as $key => $value) {
            if ($value instanceof InputFile) {
                $parts[] = $value->toMultipartPart($key);

                continue;
            }

            $parts[] = [
                'name' => $key,
                'contents' => $this->stringifyMultipartValue($this->normalizeMultipartValue($value, $fileParts, $attachmentIndex, $reservedNames)),
            ];
        }

        return array_merge($parts, $fileParts);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function containsInputFile(array $parameters): bool
    {
        return array_any(
            $parameters,
            fn (mixed $value): bool => $value instanceof InputFile
                || ($value instanceof TelegramBotData && $this->containsInputFile($value->toArray()))
                || (is_array($value) && $this->containsInputFile($value)),
        );
    }

    private function stringifyMultipartValue(mixed $value): string
    {
        return match (true) {
            is_array($value) => json_encode($value, JSON_THROW_ON_ERROR),
            is_bool($value) => $value ? 'true' : 'false',
            $value === null => '',
            default => (string) $value,
        };
    }

    /**
     * @param  list<array<string, mixed>>  $fileParts
     * @param  list<string>  $reservedNames
     */
    private function normalizeMultipartValue(mixed $value, array &$fileParts, int &$attachmentIndex, array $reservedNames): mixed
    {
        if ($value instanceof InputFile) {
            $attachmentName = $this->nextAttachmentName($attachmentIndex, $reservedNames);
            $fileParts[] = $value->toMultipartPart($attachmentName);

            return "attach://$attachmentName";
        }

        if ($value instanceof TelegramBotData) {
            return $this->normalizeMultipartValue($value->toArray(), $fileParts, $attachmentIndex, $reservedNames);
        }

        if (! is_array($value)) {
            return $value;
        }

        $normalized = [];

        foreach ($value as $key => $nestedValue) {
            $normalized[$key] = $this->normalizeMultipartValue($nestedValue, $fileParts, $attachmentIndex, $reservedNames);
        }

        return $normalized;
    }

    /**
     * @param  list<string>  $reservedNames
     */
    private function nextAttachmentName(int &$attachmentIndex, array $reservedNames): string
    {
        do {
            $name = 'file_'.$attachmentIndex++;
        } while (in_array($name, $reservedNames, true));

        return $name;
    }
}
