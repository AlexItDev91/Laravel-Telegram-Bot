<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotDoctorCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotInstallCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotMeCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotSendTestCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotUpdatesCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotWebhookDeleteCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotWebhookInfoCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\TelegramBotWebhookSetCommand;
use AlexItDev91\LaravelTelegramBot\Laravel\Http\Controllers\TelegramWebhookController;
use AlexItDev91\LaravelTelegramBot\Laravel\Http\Middleware\VerifyTelegramWebhookSecret;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookDispatcher;
use AlexItDev91\LaravelTelegramBot\TelegramBot;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class TelegramBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/telegram-bot.php', 'telegram-bot');

        $this->app->singleton(TelegramBotManager::class, function ($app): TelegramBotManager {
            return new TelegramBotManager(
                config: config('telegram-bot', []),
                clientFactory: fn (TelegramBotConfigData $config): TelegramBotClientContract => new TelegramBotClient(
                    config: $config,
                    httpClient: $app->bound(ClientInterface::class) ? $app->make(ClientInterface::class) : new Client([
                        'timeout' => $config->timeout,
                        'http_errors' => false,
                    ]),
                    logger: (bool) config('telegram-bot.logging.enabled', true) && $app->bound(LoggerInterface::class)
                        ? $app->make(LoggerInterface::class)
                        : null,
                ),
            );
        });

        $this->app->alias(TelegramBotManager::class, 'telegram-bot');
        $this->app->alias(TelegramBotManager::class, TelegramBotManagerContract::class);

        $this->app->singleton(TelegramBot::class, static function ($app): TelegramBot {
            return new TelegramBot($app->make(TelegramBotManagerContract::class));
        });

        $this->app->singleton(TelegramBotClientContract::class, static function ($app): TelegramBotClientContract {
            return $app->make(TelegramBotManager::class)->bot();
        });

        $this->app->singleton(TelegramBotClient::class, static function ($app): TelegramBotClient {
            $client = $app->make(TelegramBotManager::class)->bot();

            return $client instanceof TelegramBotClient
                ? $client
                : throw new \RuntimeException('The default Telegram Bot client is not a concrete TelegramBotClient instance.');
        });

        $this->app->singleton(TelegramWebhookDispatcher::class);
        $this->app->singleton(TelegramWebhookIdempotency::class);
        $this->app->singleton(TelegramWebhookProcessor::class);
        $this->app->singleton(TelegramBotNotificationChannel::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/telegram-bot.php' => config_path('telegram-bot.php'),
        ], 'telegram-bot-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                TelegramBotDoctorCommand::class,
                TelegramBotInstallCommand::class,
                TelegramBotMeCommand::class,
                TelegramBotSendTestCommand::class,
                TelegramBotUpdatesCommand::class,
                TelegramBotWebhookDeleteCommand::class,
                TelegramBotWebhookInfoCommand::class,
                TelegramBotWebhookSetCommand::class,
            ]);
        }

        $this->registerRouteMacro();
        $this->registerWebhookRoute();
    }

    private function registerRouteMacro(): void
    {
        Route::macro('telegramBotWebhook', function (
            string $uri = 'telegram-bot/webhook',
            ?string $name = 'telegram-bot.webhook',
            array|string $middleware = [],
        ) {
            $middleware = is_array($middleware) ? $middleware : [$middleware];
            $middleware[] = VerifyTelegramWebhookSecret::class;

            $route = Route::post(trim($uri, '/'), TelegramWebhookController::class)
                ->middleware($middleware);

            return $name !== null && $name !== '' ? $route->name($name) : $route;
        });
    }

    private function registerWebhookRoute(): void
    {
        if (! (bool) config('telegram-bot.webhook.route.enabled', true)) {
            return;
        }

        $this->registerWebhookRouteAt(
            trim((string) config('telegram-bot.webhook.route.uri', 'telegram-bot/webhook'), '/'),
            (string) config('telegram-bot.webhook.route.name', 'telegram-bot.webhook'),
            config('telegram-bot.webhook.route.middleware', []),
        );
    }

    private function registerWebhookRouteAt(string $uri, ?string $name, array|string $middleware): \Illuminate\Routing\Route
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $middleware[] = VerifyTelegramWebhookSecret::class;

        $route = Route::post(trim($uri, '/'), TelegramWebhookController::class)
            ->middleware($middleware);

        return $name !== null && $name !== '' ? $route->name($name) : $route;
    }
}
