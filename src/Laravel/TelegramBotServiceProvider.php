<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotObserver as TelegramBotObserverContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotRateLimiter as TelegramBotRateLimiterContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramConversationStore as TelegramConversationStoreContract;
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
use AlexItDev91\LaravelTelegramBot\TelegramBot;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\Support\TelegramBotRetryPolicy;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use RuntimeException;

class TelegramBotServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/telegram-bot.php', 'telegram-bot');

        $this->app->singleton(TelegramBotManager::class, function ($app): TelegramBotManager {
            $retryConfig = config('telegram-bot.retry');

            return new TelegramBotManager(
                config: config('telegram-bot', []),
                clientFactory: fn (TelegramBotConfigData $config): TelegramBotClientContract => new TelegramBotClient(
                    config: $config,
                    httpClient: $app->bound(ClientInterface::class) ? $app->make(ClientInterface::class) : new Client([
                        'timeout' => $config->timeout,
                        'http_errors' => false,
                    ]),
                    logger: config('telegram-bot.logging.enabled', true) && $app->bound(LoggerInterface::class)
                        ? $app->make(LoggerInterface::class)
                        : null,
                    retryPolicy: TelegramBotRetryPolicy::fromArray(is_array($retryConfig) ? $retryConfig : []),
                    rateLimiter: config('telegram-bot.rate_limit.enabled', false) && $app->bound(TelegramBotRateLimiterContract::class)
                        ? $app->make(TelegramBotRateLimiterContract::class)
                        : null,
                    observer: config('telegram-bot.observability.enabled', false) && $app->bound(TelegramBotObserverContract::class)
                        ? $app->make(TelegramBotObserverContract::class)
                        : null,
                ),
            );
        });

        $this->app->alias(TelegramBotManager::class, 'telegram-bot');
        $this->app->alias(TelegramBotManager::class, TelegramBotManagerContract::class);

        $this->app->singleton(TelegramBotLaravelConfig::class, static function (): TelegramBotLaravelConfig {
            return TelegramBotLaravelConfig::fromArray(config('telegram-bot', []));
        });

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
                : throw new RuntimeException('The default Telegram Bot client is not a concrete TelegramBotClient instance.');
        });

        $this->app->singleton(TelegramWebhookDispatcher::class);
        $this->app->singleton(TelegramWebhookIdempotency::class);
        $this->app->singleton(TelegramWebhookProcessor::class);
        $this->app->singleton(TelegramBotRateLimiter::class);
        $this->app->alias(TelegramBotRateLimiter::class, TelegramBotRateLimiterContract::class);
        $this->app->singleton(TelegramBotEventObserver::class);
        $this->app->alias(TelegramBotEventObserver::class, TelegramBotObserverContract::class);
        $this->app->singleton(TelegramConversationCacheStore::class);
        $this->app->alias(TelegramConversationCacheStore::class, TelegramConversationStoreContract::class);
        $this->app->singleton(TelegramConversationManager::class);
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
        if (! config('telegram-bot.webhook.route.enabled', true)) {
            return;
        }

        $this->registerWebhookRouteAt(
            trim((string) config('telegram-bot.webhook.route.uri', 'telegram-bot/webhook'), '/'),
            (string) config('telegram-bot.webhook.route.name', 'telegram-bot.webhook'),
            config('telegram-bot.webhook.route.middleware', []),
        );
    }

    private function registerWebhookRouteAt(string $uri, ?string $name, array|string $middleware): void
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $middleware[] = VerifyTelegramWebhookSecret::class;

        $route = Route::post(trim($uri, '/'), TelegramWebhookController::class)
            ->middleware($middleware);

        if ($name !== null && $name !== '') {
            $route->name($name);
        }
    }
}
