<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Http\Controllers;

use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookReceiver;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramWebhookController
{
    public function __invoke(Request $request, TelegramWebhookReceiver $receiver): Response
    {
        return $receiver->handle($request);
    }
}
