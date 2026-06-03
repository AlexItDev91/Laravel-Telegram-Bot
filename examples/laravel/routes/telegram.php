<?php

use AlexItDev91\LaravelTelegramBot\Laravel\Http\Controllers\TelegramWebhookController;
use AlexItDev91\LaravelTelegramBot\Laravel\Http\Middleware\VerifyTelegramWebhookSecret;
use Illuminate\Support\Facades\Route;

Route::post('integrations/telegram/webhook', TelegramWebhookController::class)
    ->middleware(['throttle:telegram-webhook', VerifyTelegramWebhookSecret::class])
    ->name('integrations.telegram.webhook');
