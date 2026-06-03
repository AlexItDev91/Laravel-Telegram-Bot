<?php

use Illuminate\Support\Facades\Route;

// noinspection PhpUndefinedMethodInspection
Route::telegramBotWebhook(
    uri: 'integrations/telegram/webhook',
    name: 'integrations.telegram.webhook',
    middleware: ['throttle:telegram-webhook'],
);
