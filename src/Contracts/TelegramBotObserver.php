<?php

namespace AlexItDev91\LaravelTelegramBot\Contracts;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestTelemetryData;

interface TelegramBotObserver
{
    public function record(TelegramBotRequestTelemetryData $telemetry): void;
}
