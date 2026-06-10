<?php

namespace AlexItDev91\LaravelTelegramBot\DeepLinks;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramDeepLinkException;
use Stringable;

final readonly class TelegramDeepLink implements Stringable
{
    private const int MAX_PAYLOAD_LENGTH = 64;

    private function __construct(
        private string $url,
    ) {
        //
    }

    public static function bot(string $botUsername): self
    {
        return new self('https://t.me/'.self::username($botUsername));
    }

    public static function start(string $botUsername, string $payload): self
    {
        self::assertPayload($payload);

        return new self('https://t.me/'.self::username($botUsername).'?start='.$payload);
    }

    public static function startGroup(string $botUsername, string $payload): self
    {
        self::assertPayload($payload);

        return new self('https://t.me/'.self::username($botUsername).'?startgroup='.$payload);
    }

    public static function startApp(
        string $botUsername,
        ?string $payload = null,
        ?string $appName = null,
        bool $compact = false,
    ): self {
        if ($payload !== null) {
            self::assertPayload($payload);
        }

        $path = 'https://t.me/'.self::username($botUsername);

        if ($appName !== null) {
            $path .= '/'.self::appName($appName);
        }

        $query = [];

        if ($payload !== null) {
            $query[] = 'startapp='.$payload;
        } elseif ($appName === null) {
            $query[] = 'startapp';
        }

        if ($compact) {
            $query[] = 'mode=compact';
        }

        return new self($query !== [] ? $path.'?'.implode('&', $query) : $path);
    }

    /**
     * @param  list<'users'|'bots'|'groups'|'channels'>  $choose
     */
    public static function startAttach(
        string $botUsername,
        ?string $payload = null,
        array $choose = [],
    ): self {
        if ($payload !== null) {
            self::assertPayload($payload);
        }

        $query = [$payload !== null ? 'startattach='.$payload : 'startattach'];

        if ($choose !== []) {
            self::assertChooseValues($choose);
            $query[] = 'choose='.implode('+', $choose);
        }

        return new self('https://t.me/'.self::username($botUsername).'?'.implode('&', $query));
    }

    public static function assertPayload(string $payload): void
    {
        if ($payload === '') {
            throw new TelegramDeepLinkException('Telegram deep link payload must not be empty.');
        }

        if (strlen($payload) > self::MAX_PAYLOAD_LENGTH) {
            throw new TelegramDeepLinkException('Telegram deep link payload exceeds the 64-character limit.');
        }

        if (preg_match('/\A[A-Za-z0-9_-]+\z/', $payload) !== 1) {
            throw new TelegramDeepLinkException('Telegram deep link payload may contain only A-Z, a-z, 0-9, underscore, and hyphen characters.');
        }
    }

    public function url(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

    private static function username(string $botUsername): string
    {
        $username = ltrim(trim($botUsername), '@');

        if (preg_match('/\A[A-Za-z0-9_]{5,32}\z/', $username) !== 1) {
            throw new TelegramDeepLinkException('Telegram bot username must be 5-32 characters and contain only A-Z, a-z, 0-9, and underscore characters.');
        }

        return $username;
    }

    private static function appName(string $appName): string
    {
        if (preg_match('/\A[A-Za-z0-9_]{1,64}\z/', $appName) !== 1) {
            throw new TelegramDeepLinkException('Telegram Mini App short name must contain only A-Z, a-z, 0-9, and underscore characters.');
        }

        return $appName;
    }

    /**
     * @param  list<string>  $choose
     */
    private static function assertChooseValues(array $choose): void
    {
        foreach ($choose as $value) {
            if (! in_array($value, ['users', 'bots', 'groups', 'channels'], true)) {
                throw new TelegramDeepLinkException('Telegram attachment menu choose values must be users, bots, groups, or channels.');
            }
        }
    }
}
