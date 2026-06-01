<?php

namespace AlexItDev91\LaravelTelegramBot;

use InvalidArgumentException;

final readonly class InputFile
{
    private function __construct(
        public string $path,
        public ?string $filename = null,
        public ?string $contentType = null,
    ) {
        //
    }

    public static function fromPath(string $path, ?string $filename = null, ?string $contentType = null): self
    {
        return new self($path, $filename, $contentType);
    }

    /**
     * @return array{name: string, contents: resource, filename?: string, headers?: array<string, string>}
     */
    public function toMultipartPart(string $name): array
    {
        $contents = fopen($this->path, 'r');

        if ($contents === false) {
            throw new InvalidArgumentException("Telegram input file [{$this->path}] cannot be opened for reading.");
        }

        $part = [
            'name' => $name,
            'contents' => $contents,
        ];

        if ($this->filename !== null) {
            $part['filename'] = $this->filename;
        }

        if ($this->contentType !== null) {
            $part['headers'] = ['Content-Type' => $this->contentType];
        }

        return $part;
    }
}
