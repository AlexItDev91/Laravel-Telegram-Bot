<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class TelegramBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/telegram-bot.php', 'telegram-bot');

        $this->app->singleton(TelegramBotManager::class, function (): TelegramBotManager {
            return new TelegramBotManager(
                config: config('telegram-bot', []),
                clientFactory: static fn (TelegramBotConfigData $config): TelegramBotClientContract => new TelegramBotClient(
                    config: $config,
                    httpClient: new Client([
                        'timeout' => $config->timeout,
                        'http_errors' => false,
                    ]),
                ),
            );
        });

        $this->app->alias(TelegramBotManager::class, 'telegram-bot');
        $this->app->alias(TelegramBotManager::class, TelegramBotManagerContract::class);

        $this->app->singleton(TelegramBotClientContract::class, static function ($app): TelegramBotClientContract {
            return $app->make(TelegramBotManager::class)->bot();
        });

        $this->app->singleton(TelegramBotClient::class, static function ($app): TelegramBotClient {
            $client = $app->make(TelegramBotManager::class)->bot();

            return $client instanceof TelegramBotClient
                ? $client
                : throw new \RuntimeException('The default Telegram Bot client is not a concrete TelegramBotClient instance.');
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/telegram-bot.php' => config_path('telegram-bot.php'),
        ], 'telegram-bot-config');
    }
}
