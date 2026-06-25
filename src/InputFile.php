<?php

namespace AlexItDev91\LaravelTelegramBot;

use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\Utils;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;

final readonly class InputFile
{
    private function __construct(
        public string $path,
        public ?string $filename = null,
        public ?string $contentType = null,
        private ?StreamInterface $stream = null,
    ) {
        //
    }

    public static function fromPath(string $path, ?string $filename = null, ?string $contentType = null): self
    {
        return new self($path, $filename, $contentType);
    }

    public static function fromContents(string $contents, ?string $filename = null, ?string $contentType = null): self
    {
        if ($contents === '') {
            throw new InvalidArgumentException('Telegram input file contents must not be empty.');
        }

        return new self('', $filename, $contentType, Utils::streamFor($contents));
    }

    public static function fromStream(StreamInterface $stream, ?string $filename = null, ?string $contentType = null): self
    {
        return new self('', $filename, $contentType, $stream);
    }

    public static function fromResource(mixed $resource, ?string $filename = null, ?string $contentType = null): self
    {
        if (! is_resource($resource)) {
            throw new InvalidArgumentException('Telegram input file resource must be a valid PHP stream resource.');
        }

        return new self('', $filename, $contentType, Utils::streamFor($resource));
    }

    /**
     * @return array{name: string, contents: StreamInterface, filename?: string, headers?: array<string, string>}
     */
    public function toMultipartPart(string $name): array
    {
        $stream = $this->stream ?? $this->pathStream();

        $part = [
            'name' => $name,
            'contents' => $stream,
        ];

        if ($this->filename !== null) {
            $part['filename'] = $this->filename;
        }

        if ($this->contentType !== null) {
            $part['headers'] = ['Content-Type' => $this->contentType];
        }

        return $part;
    }

    private function pathStream(): StreamInterface
    {
        $contents = @fopen($this->path, 'rb');

        if ($contents === false) {
            throw new InvalidArgumentException("Telegram input file [$this->path] cannot be opened for reading.");
        }

        fclose($contents);

        return new LazyOpenStream($this->path, 'rb');
    }
}
