<?php

namespace AlexItDev91\LaravelTelegramBot\Support;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotNotConfiguredException;

final class TelegramBotConfigResolver
{
    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public static function botConfig(array $config, string $name): array
    {
        $bots = $config['bots'] ?? [];

        if (is_array($bots) && is_array($bots[$name] ?? null)) {
            return array_merge(self::sharedBotConfig($config), self::configuredBotValues($bots[$name]));
        }

        if ($name === 'default') {
            return self::sharedBotConfig($config);
        }

        throw new TelegramBotNotConfiguredException("Telegram Bot [$name] is not configured.");
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private static function sharedBotConfig(array $config): array
    {
        return [
            'token' => $config['token'] ?? null,
            'api_url' => $config['api_url'] ?? 'https://api.telegram.org',
            'timeout' => $config['timeout'] ?? 10,
        ];
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private static function configuredBotValues(array $config): array
    {
        return array_filter($config, static fn (mixed $value): bool => $value !== null && $value !== '');
    }
}
