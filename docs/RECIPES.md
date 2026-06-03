# Production Recipes

These recipes are intended for host Laravel applications using `alexitdev91/laravel-telegram-bot`.
They keep secrets out of code and use package primitives that are covered by tests.

## Typed Outbound Payloads

Use typed outbound DTOs for common send/edit/answer calls when you want validation before the HTTP request:

```php
use AlexItDev91\LaravelTelegramBot\DTO\Messages\AnswerCallbackQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\EditMessageTextData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendDocumentData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendPhotoData;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\InputFile;

TelegramBot::bot('support')->sendMessage(new SendMessageData(
    chatId: '-1001234567890',
    text: 'Deploy finished',
    messageThreadId: '42',
));

TelegramBot::bot('support')->editMessageText(new EditMessageTextData(
    chatId: '-1001234567890',
    messageId: 55,
    text: 'Deploy finished successfully',
));

TelegramBot::bot('support')->sendPhoto(new SendPhotoData(
    chatId: '-1001234567890',
    photo: InputFile::fromPath(storage_path('app/report.jpg')),
    caption: 'Daily report',
));

TelegramBot::bot('support')->sendDocument(new SendDocumentData(
    chatId: '-1001234567890',
    document: InputFile::fromPath(storage_path('app/report.pdf')),
    caption: 'Daily report',
));

TelegramBot::bot('support')->answerCallbackQuery(new AnswerCallbackQueryData(
    callbackQueryId: 'callback-query-id',
    text: 'Saved',
    cacheTime: 30,
));
```

Configured channels still work well for repeated destinations:

```php
TelegramBot::channel('alerts')->sendMessage([
    'text' => 'Deploy finished',
]);
```

## Queue Outbound Messages And Recover From Telegram Limits

Telegram failed responses may include `retry_after` for flood limits or `migrate_to_chat_id` when a group was upgraded.
The package exposes these through `TelegramBotApiException::retryAfter()` and `migrateToChatId()`.

Use a Laravel job for outbound messages that may hit rate limits:

```php
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTelegramAlert implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private readonly string $channel,
        private readonly string $text,
    ) {
        //
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
```

## Observe Webhook Processing

The webhook pipeline dispatches Laravel events when `telegram-bot.webhook.dispatch_event` is true:

- `TelegramWebhookReceived`
- `TelegramWebhookHandled`
- `TelegramWebhookFailed`
- `TelegramWebhookQueued`
- `TelegramWebhookDuplicateSkipped`

Use listeners for metrics and tracing. Keep labels low-cardinality and do not log payload text, chat IDs, tokens, or secret headers.

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookHandled;
use Illuminate\Support\Facades\Log;

class RecordTelegramWebhookMetric
{
    public function handle(TelegramWebhookHandled $event): void
    {
        Log::info('Telegram webhook handled.', [
            'bot' => $event->botName,
            'update_type' => $event->update->type(),
        ]);
    }
}
```

## Example Files

Copy-ready examples are stored in `examples/laravel`:

- `app/Telegram/Commands/StartCommand.php`
- `app/Telegram/Handlers/CallbackQueryHandler.php`
- `app/Jobs/SendTelegramAlert.php`
- `app/Listeners/RecordTelegramWebhookMetric.php`
- `routes/telegram.php`
