<?php

namespace AlexItDev91\LaravelTelegramBot\Outbound;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\InputFile;
use InvalidArgumentException;

final class TelegramMessage implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    private function __construct(
        private readonly TelegramBotApiMethod $method,
        private array $parameters,
    ) {
        //
    }

    public static function text(string $text): self
    {
        self::assertFilledString('text', $text);

        return new self(TelegramBotApiMethod::sendMessage, [
            'text' => $text,
        ]);
    }

    public static function photo(string|InputFile $photo): self
    {
        self::assertFilledFileReference('photo', $photo);

        return new self(TelegramBotApiMethod::sendPhoto, [
            'photo' => $photo,
        ]);
    }

    public static function document(string|InputFile $document): self
    {
        self::assertFilledFileReference('document', $document);

        return new self(TelegramBotApiMethod::sendDocument, [
            'document' => $document,
        ]);
    }

    public function method(): TelegramBotApiMethod
    {
        return $this->method;
    }

    public function methodName(): string
    {
        return $this->method->value;
    }

    public function to(
        string|int $chatId,
        string|int|null $messageThreadId = null,
        string|int|null $directMessagesTopicId = null,
    ): self {
        $this->parameters['chat_id'] = $this->normalizeChatId($chatId);
        $this->parameters['message_thread_id'] = $messageThreadId;
        $this->parameters['direct_messages_topic_id'] = $directMessagesTopicId;

        return $this;
    }

    public function messageThread(string|int|null $messageThreadId): self
    {
        $this->parameters['message_thread_id'] = $messageThreadId;

        return $this;
    }

    public function directMessagesTopic(string|int|null $directMessagesTopicId): self
    {
        $this->parameters['direct_messages_topic_id'] = $directMessagesTopicId;

        return $this;
    }

    public function businessConnection(?string $businessConnectionId): self
    {
        $this->parameters['business_connection_id'] = $businessConnectionId;

        return $this;
    }

    public function caption(?string $caption): self
    {
        $this->parameters['caption'] = $caption;

        return $this;
    }

    public function parseMode(string|TelegramParseMode|null $parseMode): self
    {
        $this->parameters['parse_mode'] = $parseMode;

        return $this;
    }

    /**
     * @param  list<array<string, mixed>>|null  $entities
     */
    public function entities(?array $entities): self
    {
        $field = $this->method === TelegramBotApiMethod::sendMessage ? 'entities' : 'caption_entities';
        $this->parameters[$field] = $entities;

        return $this;
    }

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $linkPreviewOptions
     */
    public function linkPreviewOptions(TelegramBotData|array|null $linkPreviewOptions): self
    {
        $this->parameters['link_preview_options'] = $linkPreviewOptions;

        return $this;
    }

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $replyParameters
     */
    public function replyParameters(TelegramBotData|array|null $replyParameters): self
    {
        $this->parameters['reply_parameters'] = $replyParameters;

        return $this;
    }

    /**
     * @param  TelegramBotData|array<string, mixed>|null  $replyMarkup
     */
    public function replyMarkup(TelegramBotData|array|null $replyMarkup): self
    {
        $this->parameters['reply_markup'] = $replyMarkup;

        return $this;
    }

    public function silent(bool $silent = true): self
    {
        $this->parameters['disable_notification'] = $silent;

        return $this;
    }

    public function protectContent(bool $protect = true): self
    {
        $this->parameters['protect_content'] = $protect;

        return $this;
    }

    public function allowPaidBroadcast(bool $allow = true): self
    {
        $this->parameters['allow_paid_broadcast'] = $allow;

        return $this;
    }

    public function spoiler(bool $hasSpoiler = true): self
    {
        $this->parameters['has_spoiler'] = $hasSpoiler;

        return $this;
    }

    public function showCaptionAboveMedia(bool $show = true): self
    {
        $this->parameters['show_caption_above_media'] = $show;

        return $this;
    }

    public function disableContentTypeDetection(bool $disable = true): self
    {
        $this->parameters['disable_content_type_detection'] = $disable;

        return $this;
    }

    public function messageEffect(?string $messageEffectId): self
    {
        $this->parameters['message_effect_id'] = $messageEffectId;

        return $this;
    }

    public function parameter(string $key, mixed $value): self
    {
        self::assertFilledString('parameter', $key);

        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public function extra(array $parameters): self
    {
        foreach ($parameters as $key => $value) {
            if (! is_string($key)) {
                throw new InvalidArgumentException('Telegram message extra parameter keys must be strings.');
            }

            $this->parameter($key, $value);
        }

        return $this;
    }

    public function hasChatId(): bool
    {
        return array_key_exists('chat_id', $this->parameters)
            && $this->parameters['chat_id'] !== null
            && (! is_string($this->parameters['chat_id']) || trim($this->parameters['chat_id']) !== '');
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return TelegramBotRequestData::fromArray(
            TelegramBotRequestData::withoutNullValues($this->parameters),
        )->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload();
    }

    private static function assertFilledString(string $field, string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Telegram message field [$field] must not be empty.");
        }
    }

    private static function assertFilledFileReference(string $field, string|InputFile $value): void
    {
        if (is_string($value) && trim($value) === '') {
            throw new InvalidArgumentException("Telegram message field [$field] must not be empty.");
        }
    }

    private function normalizeChatId(string|int $chatId): string|int
    {
        if (is_string($chatId)) {
            self::assertFilledString('chat_id', $chatId);
        }

        return $chatId;
    }
}
