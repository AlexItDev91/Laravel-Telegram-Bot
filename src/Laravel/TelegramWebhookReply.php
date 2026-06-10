<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\AnswerCallbackQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use InvalidArgumentException;

final class TelegramWebhookReply implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    private function __construct(
        private readonly string $method,
        private array $parameters = [],
    ) {
        self::assertFilledString('method', $method);
    }

    public static function fromUpdate(TelegramWebhookUpdate $update): TelegramWebhookReplyBuilder
    {
        return new TelegramWebhookReplyBuilder($update);
    }

    /**
     * @param  array<string, mixed>|TelegramBotRequestData  $parameters
     */
    public static function method(string|TelegramBotApiMethod $method, array|TelegramBotRequestData $parameters = []): self
    {
        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        $request = $parameters instanceof TelegramBotRequestData
            ? $parameters
            : TelegramBotRequestData::fromArray($parameters);

        if ($request instanceof TelegramBotMethodRequest && $request->method() !== $methodName) {
            throw new InvalidArgumentException(sprintf(
                'Telegram Bot request DTO for method [%s] cannot be used as webhook reply method [%s].',
                $request->method(),
                $methodName,
            ));
        }

        if ($request->containsFiles()) {
            throw new InvalidArgumentException('Telegram webhook replies cannot contain InputFile uploads; send files with a bot client or queued job instead.');
        }

        $payload = $request->toArray();
        if (array_key_exists('method', $payload)) {
            throw new InvalidArgumentException('Telegram webhook reply parameter [method] is reserved.');
        }

        return new self($methodName, $payload);
    }

    public static function request(TelegramBotMethodRequest $request): self
    {
        if (! $request instanceof TelegramBotRequestData) {
            throw new InvalidArgumentException('Telegram webhook reply requests must extend TelegramBotRequestData.');
        }

        return self::method($request->method(), $request);
    }

    public static function send(TelegramMessage $message): self
    {
        if (! $message->hasChatId()) {
            throw new InvalidArgumentException('Telegram webhook message replies must define a chat_id with to().');
        }

        return self::method($message->method(), $message->payload());
    }

    public static function text(
        string $text,
        int|string $chatId,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
    ): self {
        return self::send(TelegramMessage::text($text)->to($chatId, $messageThreadId, $directMessagesTopicId));
    }

    public static function photo(
        string $photo,
        int|string $chatId,
        ?string $caption = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
    ): self {
        $message = TelegramMessage::photo($photo)->to($chatId, $messageThreadId, $directMessagesTopicId);

        if ($caption !== null) {
            $message->caption($caption);
        }

        return self::send($message);
    }

    public static function document(
        string $document,
        int|string $chatId,
        ?string $caption = null,
        int|string|null $messageThreadId = null,
        int|string|null $directMessagesTopicId = null,
    ): self {
        $message = TelegramMessage::document($document)->to($chatId, $messageThreadId, $directMessagesTopicId);

        if ($caption !== null) {
            $message->caption($caption);
        }

        return self::send($message);
    }

    public static function answerCallback(
        string $callbackQueryId,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null,
    ): self {
        return self::method(TelegramBotApiMethod::answerCallbackQuery, new AnswerCallbackQueryData(
            callbackQueryId: $callbackQueryId,
            text: $text,
            showAlert: $showAlert,
            url: $url,
            cacheTime: $cacheTime,
        ));
    }

    public function parameter(string $key, mixed $value): self
    {
        self::assertFilledString('parameter', $key);

        if ($key === 'method') {
            throw new InvalidArgumentException('Telegram webhook reply parameter [method] is reserved.');
        }

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
                throw new InvalidArgumentException('Telegram webhook reply extra parameter keys must be strings.');
            }

            $this->parameter($key, $value);
        }

        return $this;
    }

    public function methodName(): string
    {
        return $this->method;
    }

    /**
     * @return array<string, mixed>
     */
    public function parameters(): array
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
        return array_merge(['method' => $this->method], $this->parameters());
    }

    private static function assertFilledString(string $field, string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Telegram webhook reply field [$field] must not be empty.");
        }
    }
}
