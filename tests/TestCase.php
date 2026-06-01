<?php

namespace AlexItDev91\LaravelTelegramBot\Tests;

use AlexItDev91\LaravelTelegramBot\Laravel\TelegramBotServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param  Application  $app
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            TelegramBotServiceProvider::class,
        ];
    }
}
