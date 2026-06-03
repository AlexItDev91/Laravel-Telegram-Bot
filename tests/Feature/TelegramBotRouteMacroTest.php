<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class TelegramBotRouteMacroTest extends TestCase
{
    public function test_registers_manual_webhook_routes_with_secret_middleware(): void
    {
        config()->set('telegram-bot.webhook.secret_token', 'secret-token');

        Route::telegramBotWebhook('telegram/custom-webhook', 'telegram.custom-webhook');

        $this->postJson('/telegram/custom-webhook', ['update_id' => 3001])
            ->assertForbidden();

        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'secret-token')
            ->postJson('/telegram/custom-webhook', ['update_id' => 3001])
            ->assertOk()
            ->assertExactJson(['ok' => true]);

        $this->assertSame('telegram.custom-webhook', Route::getRoutes()->match(
            request()->create('/telegram/custom-webhook', 'POST'),
        )->getName());
    }
}
