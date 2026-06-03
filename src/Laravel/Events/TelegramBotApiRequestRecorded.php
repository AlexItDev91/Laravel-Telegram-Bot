<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Events;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestTelemetryData;

final readonly class TelegramBotApiRequestRecorded
{
    public function __construct(public TelegramBotRequestTelemetryData $telemetry)
    {
        //
    }
}
