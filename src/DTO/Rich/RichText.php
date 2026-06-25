<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Rich;

use Override;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use InvalidArgumentException;

/**
 * @phpstan-type RichTextValue string|TelegramBotData|array<int|string, mixed>
 */
final readonly class RichText implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(private array $payload)
    {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self($payload);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function bold(string|TelegramBotData|array $text): self
    {
        return self::withText('bold', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function italic(string|TelegramBotData|array $text): self
    {
        return self::withText('italic', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function underline(string|TelegramBotData|array $text): self
    {
        return self::withText('underline', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function strikethrough(string|TelegramBotData|array $text): self
    {
        return self::withText('strikethrough', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function spoiler(string|TelegramBotData|array $text): self
    {
        return self::withText('spoiler', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function code(string|TelegramBotData|array $text): self
    {
        return self::withText('code', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function marked(string|TelegramBotData|array $text): self
    {
        return self::withText('marked', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function subscript(string|TelegramBotData|array $text): self
    {
        return self::withText('subscript', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function superscript(string|TelegramBotData|array $text): self
    {
        return self::withText('superscript', $text);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function url(string|TelegramBotData|array $text, string $url): self
    {
        self::assertFilled('url', $url);

        return self::withText('url', $text, ['url' => $url]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function email(string|TelegramBotData|array $text, string $emailAddress): self
    {
        self::assertFilled('email_address', $emailAddress);

        return self::withText('email_address', $text, ['email_address' => $emailAddress]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function phone(string|TelegramBotData|array $text, string $phoneNumber): self
    {
        self::assertFilled('phone_number', $phoneNumber);

        return self::withText('phone_number', $text, ['phone_number' => $phoneNumber]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function bankCard(string|TelegramBotData|array $text, string $bankCardNumber): self
    {
        self::assertFilled('bank_card_number', $bankCardNumber);

        return self::withText('bank_card_number', $text, ['bank_card_number' => $bankCardNumber]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function mention(string|TelegramBotData|array $text, string $username): self
    {
        self::assertFilled('username', $username);

        return self::withText('mention', $text, ['username' => ltrim($username, '@')]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function hashtag(string|TelegramBotData|array $text, string $hashtag): self
    {
        self::assertFilled('hashtag', $hashtag);

        return self::withText('hashtag', $text, ['hashtag' => ltrim($hashtag, '#')]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function cashtag(string|TelegramBotData|array $text, string $cashtag): self
    {
        self::assertFilled('cashtag', $cashtag);

        return self::withText('cashtag', $text, ['cashtag' => ltrim($cashtag, '$')]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function botCommand(string|TelegramBotData|array $text, string $botCommand): self
    {
        self::assertFilled('bot_command', $botCommand);

        return self::withText('bot_command', $text, ['bot_command' => ltrim($botCommand, '/')]);
    }

    public static function customEmoji(string $customEmojiId, string $alternativeText): self
    {
        self::assertFilled('custom_emoji_id', $customEmojiId);
        self::assertFilled('alternative_text', $alternativeText);

        return new self([
            'type' => 'custom_emoji',
            'custom_emoji_id' => $customEmojiId,
            'alternative_text' => $alternativeText,
        ]);
    }

    public static function math(string $expression): self
    {
        self::assertFilled('expression', $expression);

        return new self([
            'type' => 'mathematical_expression',
            'expression' => $expression,
        ]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function dateTime(string|TelegramBotData|array $text, int $unixTime, string $format): self
    {
        self::assertFilled('date_time_format', $format);

        return self::withText('date_time', $text, [
            'unix_time' => $unixTime,
            'date_time_format' => $format,
        ]);
    }

    public static function anchor(string $name): self
    {
        self::assertFilled('name', $name);

        return new self([
            'type' => 'anchor',
            'name' => $name,
        ]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function anchorLink(string|TelegramBotData|array $text, string $anchorName): self
    {
        return self::withText('anchor_link', $text, ['anchor_name' => $anchorName]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function reference(string|TelegramBotData|array $text, string $name): self
    {
        self::assertFilled('name', $name);

        return self::withText('reference', $text, ['name' => $name]);
    }

    /**
     * @param  RichTextValue  $text
     */
    public static function referenceLink(string|TelegramBotData|array $text, string $referenceName): self
    {
        self::assertFilled('reference_name', $referenceName);

        return self::withText('reference_link', $text, ['reference_name' => $referenceName]);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @param  RichTextValue  $text
     * @param  array<string, mixed>  $extra
     */
    private static function withText(string $type, string|TelegramBotData|array $text, array $extra = []): self
    {
        return new self(array_merge([
            'type' => $type,
            'text' => self::richTextValue($text),
        ], $extra));
    }

    /**
     * @param  RichTextValue  $text
     * @return string|array<int|string, mixed>
     */
    private static function richTextValue(string|TelegramBotData|array $text): string|array
    {
        if ($text instanceof TelegramBotData) {
            return $text->toArray();
        }

        if (is_string($text)) {
            self::assertFilled('text', $text);

            return $text;
        }

        return array_map(
            static fn (mixed $value): mixed => $value instanceof TelegramBotData ? $value->toArray() : $value,
            $text,
        );
    }

    private static function assertFilled(string $field, string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Telegram rich text field [$field] must not be empty.");
        }
    }
}
