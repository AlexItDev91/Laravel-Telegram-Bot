<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use AlexItDev91\LaravelTelegramBot\InputFile;

final readonly class TelegramBotRequestData
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

    public function containsFiles(): bool
    {
        return $this->containsInputFile($this->parameters);
    }

    /**
     * @return array<string, mixed>
     */
    public function json(): array
    {
        return $this->parameters;
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
                $parts[] = $value->toMultipartPart((string) $key);

                continue;
            }

            $parts[] = [
                'name' => (string) $key,
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
        foreach ($parameters as $value) {
            if ($value instanceof InputFile) {
                return true;
            }

            if (is_array($value) && $this->containsInputFile($value)) {
                return true;
            }
        }

        return false;
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

            return "attach://{$attachmentName}";
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
