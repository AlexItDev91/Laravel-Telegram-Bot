<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotObserver;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestTelemetryData;
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramBotApiRequestRecorded;
use Illuminate\Contracts\Events\Dispatcher;

readonly class TelegramBotEventObserver implements TelegramBotObserver
{
    public function __construct(private Dispatcher $events)
    {
        //
    }

    #[Override]
    public function record(TelegramBotRequestTelemetryData $telemetry): void
    {
        $this->events->dispatch(new TelegramBotApiRequestRecorded($telemetry));
    }
}
