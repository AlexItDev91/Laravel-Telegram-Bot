<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Passport;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class PassportElementError implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function __construct(
        private array $parameters,
    ) {
        self::assertRequiredPayloadFields($parameters, self::requiredFields($parameters));
    }

    public static function dataField(string $type, string $fieldName, string $dataHash, string $message): self
    {
        return new self([
            'source' => 'data',
            'type' => $type,
            'field_name' => $fieldName,
            'data_hash' => $dataHash,
            'message' => $message,
        ]);
    }

    public static function frontSide(string $type, string $fileHash, string $message): self
    {
        return self::fileSource('front_side', $type, $fileHash, $message);
    }

    public static function reverseSide(string $type, string $fileHash, string $message): self
    {
        return self::fileSource('reverse_side', $type, $fileHash, $message);
    }

    public static function selfie(string $type, string $fileHash, string $message): self
    {
        return self::fileSource('selfie', $type, $fileHash, $message);
    }

    public static function file(string $type, string $fileHash, string $message): self
    {
        return self::fileSource('file', $type, $fileHash, $message);
    }

    /**
     * @param  array<int, string>  $fileHashes
     */
    public static function files(string $type, array $fileHashes, string $message): self
    {
        return new self([
            'source' => 'files',
            'type' => $type,
            'file_hashes' => $fileHashes,
            'message' => $message,
        ]);
    }

    public static function translationFile(string $type, string $fileHash, string $message): self
    {
        return self::fileSource('translation_file', $type, $fileHash, $message);
    }

    /**
     * @param  array<int, string>  $fileHashes
     */
    public static function translationFiles(string $type, array $fileHashes, string $message): self
    {
        return new self([
            'source' => 'translation_files',
            'type' => $type,
            'file_hashes' => $fileHashes,
            'message' => $message,
        ]);
    }

    public static function unspecified(string $type, string $elementHash, string $message): self
    {
        return new self([
            'source' => 'unspecified',
            'type' => $type,
            'element_hash' => $elementHash,
            'message' => $message,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return self::payload($this->parameters);
    }

    private static function fileSource(string $source, string $type, string $fileHash, string $message): self
    {
        return new self([
            'source' => $source,
            'type' => $type,
            'file_hash' => $fileHash,
            'message' => $message,
        ]);
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return list<string>
     */
    private static function requiredFields(array $parameters): array
    {
        return match ($parameters['source'] ?? null) {
            'data' => ['source', 'type', 'field_name', 'data_hash', 'message'],
            'files', 'translation_files' => ['source', 'type', 'file_hashes', 'message'],
            'unspecified' => ['source', 'type', 'element_hash', 'message'],
            default => ['source', 'type', 'file_hash', 'message'],
        };
    }
}
