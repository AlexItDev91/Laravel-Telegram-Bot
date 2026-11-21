<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Messages;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use Override;

final readonly class InputSharedWidgetMessageContent implements TelegramBotData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  list<TelegramBotData|array<string, mixed>>|null  $captionEntities
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private string $widgetId,
        private ?string $chatData = null,
        private ?string $caption = null,
        private TelegramParseMode|string|null $parseMode = null,
        private ?array $captionEntities = null,
        private ?bool $showCaptionAboveMedia = null,
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
            'widget_id' => $this->widgetId,
            'chat_data' => $this->chatData,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode,
            'caption_entities' => $this->captionEntities,
            'show_caption_above_media' => $this->showCaptionAboveMedia,
        ], $this->extra, ['widget_id']);
    }
}
