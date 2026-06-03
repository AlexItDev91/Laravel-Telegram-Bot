<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

final readonly class LinkPreviewOptions implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private ?bool $isDisabled = null,
        private ?string $url = null,
        private ?bool $preferSmallMedia = null,
        private ?bool $preferLargeMedia = null,
        private ?bool $showAboveText = null,
        private array $extra = [],
    ) {
        //
    }

    public static function disabled(): self
    {
        return new self(isDisabled: true);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'is_disabled' => $this->isDisabled,
            'url' => $this->url,
            'prefer_small_media' => $this->preferSmallMedia,
            'prefer_large_media' => $this->preferLargeMedia,
            'show_above_text' => $this->showAboveText,
        ], $this->extra);
    }
}
