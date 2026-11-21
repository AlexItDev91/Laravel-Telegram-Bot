<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use Override;

final readonly class InputWidgetMessageContent implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  list<TelegramBotData|array<string, mixed>>|null  $captionEntities
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string $html,
        private ?int $width = null,
        private ?int $height = null,
        private ?string $publicData = null,
        private ?string $chatData = null,
        private ?string $caption = null,
        private TelegramParseMode|string|null $parseMode = null,
        private ?array $captionEntities = null,
        private ?bool $showCaptionAboveMedia = null,
        private ?bool $allowQueries = null,
        private ?bool $isShared = null,
        private array $extra = [],
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return self::payload([
            'html' => $this->html,
            'width' => $this->width,
            'height' => $this->height,
            'public_data' => $this->publicData,
            'chat_data' => $this->chatData,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode,
            'caption_entities' => $this->captionEntities,
            'show_caption_above_media' => $this->showCaptionAboveMedia,
            'allow_queries' => $this->allowQueries,
            'is_shared' => $this->isShared,
        ], $this->extra, ['html']);
    }
}
