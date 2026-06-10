# Production Recipes

These recipes are intended for host Laravel applications using `alexitdev91/laravel-telegram-bot`.
They keep secrets out of code and use package primitives that are covered by tests.

## Typed Outbound Payloads

Use typed outbound DTOs for common send/edit/answer calls when you want validation before the HTTP request:

```php
use AlexItDev91\LaravelTelegramBot\DTO\Messages\AnswerCallbackQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\EditMessageTextData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\LinkPreviewOptions;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendDocumentData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendPhotoData;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\InputFile;

final class TelegramOpsNotifier
{
    private const string BOT = 'support';

    private const string BUTTON_RETRY = 'Retry';

    private const string CALLBACK_QUERY_ID = 'callback-query-id';

    private const string CALLBACK_RETRY = 'deploy:retry';

    private const string CHAT_ID = '-1001234567890';

    private const string MESSAGE_THREAD_ID = '42';

    private const string PHOTO_CAPTION = 'Daily report';

    private const string PHOTO_PATH = 'app/report.jpg';

    private const string REPORT_PATH = 'app/report.pdf';

    private const string TEXT_CALLBACK_SAVED = 'Saved';

    private const string TEXT_DEPLOY_FINISHED = 'Deploy finished';

    private const string TEXT_DEPLOY_SUCCEEDED = 'Deploy finished successfully';

    public function send(): void
    {
        TelegramBot::bot(self::BOT)->sendMessage(new SendMessageData(
            chatId: self::CHAT_ID,
            text: self::TEXT_DEPLOY_FINISHED,
            messageThreadId: self::MESSAGE_THREAD_ID,
            linkPreviewOptions: LinkPreviewOptions::disabled(),
            replyMarkup: InlineKeyboardMarkup::singleButton(
                InlineKeyboardButton::callback(self::BUTTON_RETRY, self::CALLBACK_RETRY),
            ),
        ));

        TelegramBot::bot(self::BOT)->editMessageText(new EditMessageTextData(
            chatId: self::CHAT_ID,
            messageId: 55,
            text: self::TEXT_DEPLOY_SUCCEEDED,
        ));

        TelegramBot::bot(self::BOT)->sendPhoto(new SendPhotoData(
            chatId: self::CHAT_ID,
            photo: InputFile::fromPath(storage_path(self::PHOTO_PATH)),
            caption: self::PHOTO_CAPTION,
        ));

        TelegramBot::bot(self::BOT)->sendDocument(new SendDocumentData(
            chatId: self::CHAT_ID,
            document: InputFile::fromPath(storage_path(self::REPORT_PATH)),
            caption: self::PHOTO_CAPTION,
        ));

        TelegramBot::bot(self::BOT)->answerCallbackQuery(new AnswerCallbackQueryData(
            callbackQueryId: self::CALLBACK_QUERY_ID,
            text: self::TEXT_CALLBACK_SAVED,
            cacheTime: 30,
        ));
    }
}
```

Configured channels still work well for repeated destinations:

```php
TelegramBot::channel('alerts')->sendMessage([
    'text' => 'Deploy finished',
]);
```

## Fluent Outbound Messages

Use `Outbound\TelegramMessage` when the application sends common text, photo, or document messages and the destination should remain explicit:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;

final class TelegramFluentNotifier
{
    private const string TEXT_DEPLOY_FINISHED = 'Deploy finished';

    private const string PHOTO_FILE_ID = 'photo-file-id';

    private const string DOCUMENT_FILE_ID = 'document-file-id';

    public function send(string $tenantBotToken): void
    {
        TelegramBot::channel('alerts')->send(
            TelegramMessage::text(self::TEXT_DEPLOY_FINISHED),
        );

        TelegramBot::to('-1001234567890', token: $tenantBotToken)->send(
            TelegramMessage::photo(self::PHOTO_FILE_ID)
                ->caption('Daily report')
                ->silent(),
        );

        TelegramBot::botToken($tenantBotToken)->send(
            TelegramMessage::document(self::DOCUMENT_FILE_ID)
                ->to('-1001234567890')
                ->caption('Invoice')
                ->protectContent(),
        );
    }
}
```

The builder supports `text()`, `photo()`, `document()`, `to()`, `messageThread()`, `directMessagesTopic()`, `caption()`, `parseMode()`, `entities()`, `linkPreviewOptions()`, `replyParameters()`, `replyMarkup()`, `silent()`, `protectContent()`, `allowPaidBroadcast()`, and `extra()`.
Use method-scoped request DTOs or raw `call(method, parameters)` when a flow needs fields outside the fluent convenience surface.

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
Generated request builders bind well-known Telegram string domains to enums, including `TelegramParseMode`, `TelegramChatAction`, `TelegramPollType`, `TelegramStickerType`, `TelegramStickerFormat`, and `TelegramUpdateType`.

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
Use the Typed Responses topic for the full typed response helper list.

When no dedicated result DTO exists yet, `callData()` wraps associative Telegram objects in `TelegramBotResultData` and lists of objects in `list<TelegramBotResultData>`. Scalars and raw `call()` results remain unchanged.

## Laravel Notifications

Use the notification channel when Telegram delivery belongs to a notifiable model or an on-demand Laravel notification:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class DeployFinished extends Notification
{
    private const string CHANNEL = 'alerts';

    private const string TEXT = 'Deploy finished';

    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text(self::TEXT)
            ->channel(self::CHANNEL)
            ->parseMode(TelegramParseMode::HTML);
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

Use `TelegramConversationWizard` from handlers when the flow is a form or guided sequence:

```php
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Laravel\Conversation\TelegramConversationWizard;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramConversationManager;

final readonly class ProfileWizardHandler
{
    public function __construct(private TelegramConversationManager $conversations)
    {
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        $chatId = $update->effectiveChat()?->id();

        if ($chatId === null) {
            return ['ok' => true];
        }

        $wizard = TelegramConversationWizard::for($this->conversations->workflowForUpdate($update, $botName))
            ->timeout(600)
            ->cancelledMessage('Profile setup cancelled.');

        $wizard->step('email', 'email')
            ->prompt('Send your support email address.')
            ->invalid('That does not look like an email address. Try again.')
            ->validate(static fn (mixed $value): bool => is_string($value) && str_contains($value, '@'))
            ->next('summary');

        $wizard->step('summary', 'summary')
            ->prompt('Describe the support request.')
            ->complete('Support request saved.');

        $result = $wizard->handle($update);

        return $result->hasMessage()
            ? ['method' => 'sendMessage', 'chat_id' => $chatId, 'text' => $result->message()]
            : ['ok' => true];
    }
}
```

The wizard starts the first step when no state exists, stores typed step values in the conversation context, keeps state across webhook retries, validates input with callbacks, supports `/cancel` and `/back` by default, and reads callback-query `data` before message text for inline keyboard transitions.

Common form shapes:

- Profile setup: `email -> timezone -> complete`.
- Order intake: `product -> quantity -> delivery window -> confirmation`.
- Support request collection: `category callback button -> free-text summary -> priority callback button`.

Use the lower-level `TelegramConversationManager` and `TelegramConversationWorkflow` APIs directly when a flow needs custom state machines, cross-chat keys, or transitions that do not map to linear form steps. Conversation keys are namespaced by bot and the effective chat/user when Telegram provides them. The store is disabled by default so existing webhook handlers keep their current stateless behavior.

## Human Handoff

Use `TelegramHumanHandoff` when automation should pause and a human operator needs enough context to continue in a private support chat, forum topic, or external ticket system. The helper is only a contract around conversation state and operator summaries; it does not require a CRM or support database.

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Handoff\TelegramHumanHandoff;

$workflow = $this->conversations->workflowForUpdate($update, $botName);

if (TelegramHumanHandoff::fromWorkflow($workflow) !== null) {
    return ['ok' => true];
}

$handoff = TelegramHumanHandoff::fromUpdate($update, 'billing-question', [
    'ticket_id' => $ticket->public_id,
    'summary' => $ticket->summary,
]);

$handoff->open($workflow, [
    'resume_state' => 'support-summary',
], ttl: 86400);

$this->telegram->channel('support')->sendMessage([
    'text' => $handoff->operatorText('Support handoff'),
]);
```

When the operator destination is not configured as a package channel, send the typed payload directly:

```php
$this->telegram->bot($botName)->sendMessage(
    $handoff->toOperatorMessage(
        chatId: '-1001234567890',
        messageThreadId: 42,
        title: 'Support handoff',
    ),
);
```

To close a handoff, load the original user workflow from your ticket or conversation mapping, then reset or resume automation:

```php
$handoff = TelegramHumanHandoff::fromWorkflow($workflow);
$resumeState = $workflow->context()->string('resume_state');

TelegramHumanHandoff::close($workflow);

if ($handoff !== null && $resumeState !== null) {
    $workflow->start($resumeState, ['handoff_closed_at' => now()->toISOString()]);
}
```

Queue operator notifications when the support chat is busy, keep the original user workflow key or ticket ID in host app storage, and avoid copying raw message text, phone numbers, payment details, or documents into operator summaries unless the operator genuinely needs them. Prefer private groups or forum topics with limited members, keep bot tokens out of tickets and logs, and use `TelegramBot::fake()` assertions to verify that handoff messages do not leak secrets.

## Scenario Recipes

### Operations Alerts

Use configured channels and queued jobs for infrastructure, deploy, billing, and security alerts. Keep alert text short, put links to internal dashboards in your own app, and let `SendTelegramAlert` own retries and failed-job visibility.

```php
final readonly class DispatchOpsAlert
{
    public function __invoke(Incident $incident): void
    {
        SendTelegramAlert::dispatch('alerts', sprintf(
            'Incident %s: %s',
            $incident->public_id,
            $incident->status,
        ));
    }
}
```

```php
$fake = TelegramBot::fake();

SendTelegramAlert::dispatchSync('alerts', 'Incident INC-1001: open');

$fake->assertSentMessageToChannel('alerts', static function (array $parameters): bool {
    return $parameters['text'] === 'Incident INC-1001: open';
});
$fake->assertNoTokenLeakage();
```

### Ecommerce Order Updates

Use dynamic destinations when each merchant, store, or tenant owns a bot token. Keep the token in tenant secret storage, send order status updates through the fluent builder, and use typed payment DTOs for invoices and pre-checkout paths.

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;

TelegramBot::to($order->telegram_chat_id, token: $order->store->telegram_bot_token)
    ->send(TelegramMessage::text(sprintf(
        'Order %s is now %s.',
        $order->public_id,
        $order->status,
    ))->silent());
```

```php
$fake = TelegramBot::fake();

TelegramBot::to('123456789', token: '111:tenant-token')
    ->send(TelegramMessage::text('Order ORD-1001 is now shipped.'));

$fake->assertSent('sendMessage', ['text' => 'Order ORD-1001 is now shipped.']);
$fake->assertNoTokenLeakage();
```

### Support Intake

Use `TelegramConversationWizard` for structured intake, then open `TelegramHumanHandoff` only when automation cannot resolve the case. Store a public ticket ID instead of raw private messages.

```php
$wizard = TelegramConversationWizard::for($this->conversations->workflowForUpdate($update, $botName));
$wizard->step('category', 'category')->prompt('Choose a support category.')->next('summary');
$wizard->step('summary', 'summary')->prompt('Describe the issue.')->complete('Support request saved.');

$result = $wizard->handle($update);

if ($result->completed() && $result->context()->string('category') === 'human') {
    TelegramHumanHandoff::fromUpdate($update, 'support-intake', [
        'ticket_id' => $ticket->public_id,
    ])->open($this->conversations->workflowForUpdate($update, $botName));
}
```

```php
$this->assertSame(TelegramHumanHandoff::STATE, $workflow->state());
$fake->assertNoTokenLeakage();
```

### Admin-Channel Notifications

Use an admin channel or forum topic for moderation, approvals, and operational controls. Pair the message with callback buttons and handle callbacks through the webhook dispatcher with admin middleware.

```php
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;

private const string BUTTON_TEXT = 'Acknowledge';
private const string BUTTON_DATA = 'incident:ack';

TelegramBot::channel('admins')->send(
    TelegramMessage::text('New admin action required.')
        ->replyMarkup(InlineKeyboardMarkup::singleButton(
            InlineKeyboardButton::callback(self::BUTTON_TEXT, self::BUTTON_DATA),
        )),
);
```

```php
$fake = TelegramBot::fake();

TelegramBot::channel('admins')->send(TelegramMessage::text('New admin action required.'));

$fake->assertSentMessageToChannel('admins', static function (array $parameters): bool {
    return $parameters['text'] === 'New admin action required.';
});
```

Routes can return a configured package `channel`, a named `bot` plus `chat_id`, or a plain `chat_id`.
Use the Notifications topic for the full routing and payload guide.

## Queue Outbound Messages And Recover From Telegram Limits

Telegram failed responses may include `retry_after` for flood limits or `migrate_to_chat_id` when a group was upgraded.
The package exposes these through `TelegramBotApiException::retryAfter()` and `migrateToChatId()`.

Use a Laravel job for outbound messages that may hit rate limits:

```php
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotRateLimitException;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendTelegramAlert implements ShouldQueue, ShouldBeUnique
{
    use InteractsWithQueue;
    use Queueable;

    public int $tries = 5;
    public int $maxExceptions = 3;
    public int $uniqueFor = 300;

    public function __construct(
        private readonly string $channel,
        private readonly string $text,
    ) {
        //
    }

    public function uniqueId(): string
    {
        return hash('sha256', $this->channel.'|'.$this->text);
    }

    public function backoff(): array
    {
        return [10, 60, 300];
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
        } catch (TelegramBotRateLimitException $exception) {
            $this->release($exception->availableIn());
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Telegram alert delivery failed.', [
            'channel' => $this->channel,
            'exception' => $exception::class,
        ]);
    }
}
```

Use queue-level uniqueness when the same alert can be dispatched repeatedly by retries, monitors, or domain events. Keep `uniqueId()` free of tokens and raw private text when possible; use a stable domain ID such as an order ID, alert ID, or ticket ID for customer-facing systems. Let failed jobs remain visible in Laravel Horizon, failed_jobs, or your queue backend instead of swallowing non-retryable Telegram errors.

Enable SDK retry and local rate limiting when one worker can send bursts:

```php
'retry' => [
    'enabled' => true,
    'max_attempts' => 2,
    'sleep' => false,
],
'rate_limit' => [
    'enabled' => true,
    'store' => 'redis',
    'max_attempts' => 30,
    'decay_seconds' => 1,
],
```

Use `TelegramBot::fake()` in job tests to assert `sendMessage`, `assertSentSequence()`, `assertSentTypedPayload()`, and `assertNoTokenLeakage()` for queue paths without calling Telegram.

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
- `app/Telegram/Commands/BuyCommand.php`
- `app/Telegram/Handlers/CallbackQueryHandler.php`
- `app/Telegram/Handlers/ProfileWizardHandler.php`
- `app/Telegram/Middleware/EnsureTelegramWebhookEnabled.php`
- `app/Notifications/TelegramDeployFinished.php`
- `app/Jobs/SendTelegramAlert.php`
- `app/Listeners/RecordTelegramWebhookMetric.php`
- `routes/telegram.php`
- `tests/Feature/TelegramBotExampleTest.php`

## Generated Request Builders

Every Bot API method has a generated request class under `AlexItDev91\LaravelTelegramBot\DTO\Requests`.
Use these when you want IDE-friendly named arguments for methods that do not have a hand-written rich DTO:

```php
use AlexItDev91\LaravelTelegramBot\DTO\Requests\SendMessageRequestData;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('support')->sendMessage(SendMessageRequestData::make(
    chatId: '-1001234567890',
    text: 'Ticket created.',
    extra: ['future_optional_parameter' => 'kept'],
));
```

## Webhook Router V2

Use route-level middleware, grouped handlers, update-type fallbacks, or attribute discovery:

```php
'webhook' => [
    'commands' => [
        'start' => [
            'handler' => App\Telegram\Commands\StartCommand::class,
            'middleware' => [App\Telegram\Middleware\EnsureTelegramWebhookEnabled::class],
        ],
    ],
    'groups' => [
        'admin' => [
            'middleware' => [App\Telegram\Middleware\EnsureTelegramAdmin::class],
            'handlers' => [
                'chat_member' => App\Telegram\Handlers\AdminChatMemberHandler::class,
            ],
        ],
    ],
    'fallback_handlers' => [
        'message' => App\Telegram\Handlers\UnknownMessageHandler::class,
    ],
],
```

## Retry, Rate Limit, And API Observability

Enable retry for retryable Telegram responses and transient transport failures:

```php
'retry' => [
    'enabled' => true,
    'max_attempts' => 2,
    'sleep' => false,
],
```

Enable the local Laravel-friendly rate limiter before outbound bursts hit Telegram:

```php
'rate_limit' => [
    'enabled' => true,
    'store' => 'redis',
    'max_attempts' => 30,
    'decay_seconds' => 1,
],
```

Enable API observability to dispatch `TelegramBotApiRequestRecorded` events with method, status, duration, attempts, and file flag. The telemetry does not include chat IDs, message text, tokens, or webhook secrets.

## Cookbook Bots

Admin bot:
Use grouped webhook handlers with an admin middleware, handle `/stats`, and keep all metric labels to bot name, method, and update type.

Support bot:
Use `ProfileWizardHandler` style conversations for multi-step intake, `TelegramBot::fake()` in tests, and channel defaults for team inbox topics.

Marketplace or payments bot:
Use a `/buy` command that sends `SendInvoiceData`, answer pre-checkout and shipping queries with typed DTOs, and keep provider tokens in host app secrets only.

Channel broadcaster:
Use configured channels, queue outbound jobs, retry on `retry_after`, and handle `migrate_to_chat_id` by updating host app configuration.

Mini App backend:
Use web app or callback query handlers for signed Mini App flows, keep raw update payloads out of logs, and respond through typed `answerCallbackQuery` or message edit DTOs.

## Testing DSL 2.0

```php
$fake = TelegramBot::fake();

TelegramBot::sendMessage(SendMessageRequestData::make(
    chatId: '123456789',
    text: 'Hello',
));

$fake->assertSent('sendMessage', ['text' => 'Hello'], times: 1);
$fake->assertSentTypedPayload('sendMessage', SendMessageRequestData::class);
$fake->assertSentSequence(['sendMessage']);
$fake->assertNoTokenLeakage();
```
