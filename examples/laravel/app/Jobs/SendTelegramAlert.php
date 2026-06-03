<?php

namespace App\Jobs;

use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTelegramAlert implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $channel,
        private readonly string $text,
    ) {
        $this->onQueue('telegram-outbound');
    }

    public function handle(): void
    {
        try {
            TelegramBot::channel($this->channel)->sendMessage([
                'text' => $this->text,
            ]);
        } catch (TelegramBotApiException $exception) {
            if ($exception->retryAfter() !== null) {
                $this->release($exception->retryAfter());

                return;
            }

            if ($exception->migrateToChatId() !== null) {
                Log::warning('Telegram chat migrated. Update the configured channel chat_id.', [
                    'channel' => $this->channel,
                    'migrate_to_chat_id' => (string) $exception->migrateToChatId(),
                ]);
            }

            throw $exception;
        }
    }
}
