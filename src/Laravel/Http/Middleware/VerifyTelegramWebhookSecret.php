<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class VerifyTelegramWebhookSecret
{
    public function __construct(
        private readonly ?LoggerInterface $logger = null,
    ) {
        //
    }

    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('telegram-bot.webhook.secret_token');
        $requireSecret = config('telegram-bot.webhook.require_secret');
        $requireSecret ??= config('app.env') === 'production';

        if ($expected === null || $expected === '') {
            if ((bool) $requireSecret) {
                $this->warning('Telegram webhook rejected because secret token enforcement is enabled but no secret is configured.', $request);

                abort(403, 'Telegram webhook secret token is required.');
            }

            return $next($request);
        }

        $actual = $request->header('X-Telegram-Bot-Api-Secret-Token', '');

        if (! is_string($actual) || ! hash_equals((string) $expected, $actual)) {
            $this->warning('Telegram webhook rejected because the secret token is invalid.', $request);

            abort(403, 'Invalid Telegram webhook secret token.');
        }

        return $next($request);
    }

    private function warning(string $message, Request $request): void
    {
        if (! (bool) config('telegram-bot.logging.enabled', true)) {
            return;
        }

        $this->logger?->warning($message, [
            'path' => $request->path(),
            'has_secret_header' => $request->hasHeader('X-Telegram-Bot-Api-Secret-Token'),
        ]);
    }
}
