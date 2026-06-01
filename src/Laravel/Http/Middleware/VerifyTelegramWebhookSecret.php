<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTelegramWebhookSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('telegram-bot.webhook.secret_token');

        if ($expected === null || $expected === '') {
            return $next($request);
        }

        $actual = $request->header('X-Telegram-Bot-Api-Secret-Token', '');

        if (! is_string($actual) || ! hash_equals((string) $expected, $actual)) {
            abort(403, 'Invalid Telegram webhook secret token.');
        }

        return $next($request);
    }
}
