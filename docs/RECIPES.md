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

## Method-Scoped Request DTOs

Use `TelegramBotRequestData::forMethod()` when a method does not have a dedicated outbound DTO yet, but the host application still wants generated Bot API parameter validation:

```php
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('support')->sendMessage(
    TelegramBotRequestData::forMethod(TelegramBotApiMethod::sendMessage, [
        'chat_id' => '-1001234567890',
        'text' => 'Deploy finished',
    ]),
);
```

The generated `TelegramBotApiMethodSchema` covers all 176 Bot API 10.0 methods and 863 documented parameters. It validates required parameters and prevents a DTO scoped to one method from being sent through another method. For configured channels that merge `chat_id` or topic defaults after DTO creation, pass `validateRequiredParameters: false`.

## Typed Response Accessors

Use typed response helpers when the host application needs stable DTO accessors for returned Telegram objects:

```php
$message = TelegramBot::channel('alerts')->sendMessageData([
    'text' => 'Deploy finished',
]);

$messageId = $message->messageId();
$chatId = $message->chat()?->id();
$text = $message->text();

$webhook = TelegramBot::getWebhookInfoData();
$pendingUpdates = $webhook->pendingUpdateCount();
```

Raw methods such as `sendMessage()` and `getWebhookInfo()` still return Telegram's decoded `result` unchanged.
Use `docs/RESPONSES.md` for the full typed response helper list.

When no dedicated result DTO exists yet, `callData()` wraps associative Telegram objects in `TelegramBotResultData` and lists of objects in `list<TelegramBotResultData>`. Scalars and raw `call()` results remain unchanged.

## Laravel Notifications

Use the notification channel when Telegram delivery belongs to a notifiable model or an on-demand Laravel notification:

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class DeployFinished extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text('Deploy finished')
            ->channel('alerts')
            ->parseMode('HTML');
    }
}
```

Routes can return a configured package `channel`, a named `bot` plus `chat_id`, or a plain `chat_id`.
Use `docs/NOTIFICATIONS.md` for the full routing and payload guide.

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

## Webhook Middleware

Use `TelegramWebhookMiddleware` for cross-cutting work that should run before the configured handler, dispatcher, command map, or fallback:

```php
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookMiddleware;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use Closure;

final class ResolveTelegramTenant implements TelegramWebhookMiddleware
{
    public function process(TelegramWebhookUpdate $update, string $botName, Closure $next): mixed
    {
        app()->instance('telegram.tenant_key', (string) ($update->effectiveChat()?->id() ?? $botName));

        return $next($update, $botName);
    }
}
```

Register middleware in order:

```php
'webhook' => [
    'middleware' => [
        App\Telegram\Middleware\ResolveTelegramTenant::class,
    ],
],
```

Middleware may return its own response to short-circuit downstream handlers.

## Conversations

Enable the cache-backed conversation store when webhook handlers need per-chat or per-user state:

```env
TELEGRAM_CONVERSATION_ENABLED=true
TELEGRAM_CONVERSATION_STORE=redis
TELEGRAM_CONVERSATION_TTL=86400
TELEGRAM_CONVERSATION_KEY_PREFIX=telegram-bot:conversation
```

Use `TelegramConversationManager` from handlers:

```php
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;

final readonly class ProfileWizardHandler
{
    public function __construct(private TelegramConversationManager $conversations)
    {
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $conversation = $this->conversations->forUpdate($update, $botName);

        if ($conversation?->state() === 'awaiting_email') {
            $this->conversations->forgetForUpdate($update, $botName);

            return ['ok' => true];
        }

        $this->conversations->putForUpdate($update, $botName, 'awaiting_email');

        return ['ok' => true];
    }
}
```

Conversation keys are namespaced by bot and the effective chat/user when Telegram provides them. The store is disabled by default so existing webhook handlers keep their current stateless behavior.

## Example Files

Copy-ready examples are stored in `examples/laravel`:

- `app/Telegram/Commands/StartCommand.php`
- `app/Telegram/Handlers/CallbackQueryHandler.php`
- `app/Notifications/TelegramDeployFinished.php`
- `app/Jobs/SendTelegramAlert.php`
- `app/Listeners/RecordTelegramWebhookMetric.php`
- `routes/telegram.php`
