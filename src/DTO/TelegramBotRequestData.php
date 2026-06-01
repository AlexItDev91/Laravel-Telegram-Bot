<?php

namespace Aptenova\TelegramBot\DTO;

use Aptenova\TelegramBot\InputFile;

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

        foreach ($this->parameters as $key => $value) {
            if ($value instanceof InputFile) {
                $parts[] = $value->toMultipartPart($key);

                continue;
            }

            $parts[] = [
                'name' => $key,
                'contents' => $this->stringifyMultipartValue($value),
            ];
        }

        return $parts;
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
}
