# Telegram Webhooks

This package supports both sides of Telegram webhooks:

- outgoing Bot API methods such as `setWebhook`, `deleteWebhook`, and `getWebhookInfo`;
- an optional Laravel webhook receiver route for incoming Telegram `Update` payloads.

Primary Telegram references:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [setWebhook](https://core.telegram.org/bots/api#setwebhook)
- [Update](https://core.telegram.org/bots/api#update)

## Configuration

Publish the package config and set the webhook values:

```text
TELEGRAM_WEBHOOK_BOT=default
TELEGRAM_BOT_LOGGING_ENABLED=true
TELEGRAM_WEBHOOK_SECRET_TOKEN=change-this-secret
TELEGRAM_WEBHOOK_REQUIRE_SECRET=true
TELEGRAM_WEBHOOK_ROUTE_ENABLED=true
TELEGRAM_WEBHOOK_ROUTE_URI=telegram-bot/webhook
TELEGRAM_WEBHOOK_ROUTE_NAME=telegram-bot.webhook
```

`config/telegram-bot.php` contains:

```php
'logging' => [
    'enabled' => env('TELEGRAM_BOT_LOGGING_ENABLED', true),
],

'webhook' => [
    'bot' => env('TELEGRAM_WEBHOOK_BOT', env('TELEGRAM_BOT', 'default')),
    'secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN'),
    'require_secret' => env('TELEGRAM_WEBHOOK_REQUIRE_SECRET', env('APP_ENV') === 'production'),
    'handler' => App\Telegram\TelegramWebhookHandler::class,
    'dispatch_event' => true,
    'route' => [
        'enabled' => env('TELEGRAM_WEBHOOK_ROUTE_ENABLED', true),
        'uri' => env('TELEGRAM_WEBHOOK_ROUTE_URI', 'telegram-bot/webhook'),
        'name' => env('TELEGRAM_WEBHOOK_ROUTE_NAME', 'telegram-bot.webhook'),
        'middleware' => [],
    ],
],
```

The route defaults to `POST /telegram-bot/webhook` and is protected by `X-Telegram-Bot-Api-Secret-Token` when `secret_token` is configured. When `require_secret` is true, the middleware rejects webhook requests if no secret is configured; this defaults to true when `APP_ENV=production`.

The configured secret token is validated against Telegram's contract before it is accepted: 1-256 characters, using only `A-Z`, `a-z`, `0-9`, `_`, and `-`. Invalid configured secrets fail closed and are not logged.

## Register The Webhook With Telegram

Call `setWebhook` from a deployment command, Tinker, or your own release automation:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('default')->setWebhook([
    'url' => route('telegram-bot.webhook'),
    'secret_token' => config('telegram-bot.webhook.secret_token'),
    'allowed_updates' => [
        'message',
        'callback_query',
        'my_chat_member',
    ],
]);
```

The secret token must be 1-256 characters and may contain only `A-Z`, `a-z`, `0-9`, `_`, and `-`.

The same operation is available through an interactive Artisan command:

```bash
php artisan telegram-bot:webhook:set
```

Non-interactive example:

```bash
php artisan telegram-bot:webhook:set \
  --bot=default \
  --url=https://example.com/telegram-bot/webhook \
  --secret="${TELEGRAM_WEBHOOK_SECRET_TOKEN}" \
  --allowed-updates=message \
  --allowed-updates=callback_query
```

Use `getWebhookInfo()` to inspect webhook status:

```php
$info = TelegramBot::bot('default')->getWebhookInfo();
```

Or:

```bash
php artisan telegram-bot:webhook:info --bot=default
```

Use `deleteWebhook()` to switch back to `getUpdates`:

```php
TelegramBot::bot('default')->deleteWebhook([
    'drop_pending_updates' => false,
]);
```

Or:

```bash
php artisan telegram-bot:webhook:delete --bot=default --yes
```

See [docs/CONSOLE_COMMANDS.md](CONSOLE_COMMANDS.md) for all command options.

## Handle Incoming Updates

Create a handler class:

```php
namespace App\Telegram;

use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookHandler as TelegramWebhookHandlerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final readonly class TelegramWebhookHandler implements TelegramWebhookHandlerContract
{
    public function __construct(
        private TelegramBot $telegram,
    ) {
    }

    public function handle(TelegramWebhookUpdate $update, string $botName): mixed
    {
        if ($update->type() !== 'message') {
            return null;
        }

        $chatId = $update->get('message.chat.id');
        $text = $update->get('message.text');

        if ($chatId !== null && $text === '/start') {
            $this->telegram->bot($botName)->sendMessage([
                'chat_id' => (string) $chatId,
                'text' => 'Ready.',
            ]);
        }

        return ['ok' => true];
    }
}
```

Then register it in `config/telegram-bot.php`:

```php
'handler' => App\Telegram\TelegramWebhookHandler::class,
```

The handler may return:

- `null` or `true`: the package returns `{"ok": true}`;
- an array: the package returns it as JSON;
- a string: the package returns it as a plain response;
- a Symfony/Laravel `Response`: the package returns it directly.

Telegram only requires a successful `2xx` response. Keep webhook handlers fast; dispatch jobs for slow work.

## Events

When `dispatch_event` is true, every valid update dispatches:

```php
AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived
```

Example listener registration:

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Events\TelegramWebhookReceived;
use Illuminate\Support\Facades\Event;

Event::listen(TelegramWebhookReceived::class, function (TelegramWebhookReceived $event): void {
    $type = $event->update->type();
    $bot = $event->botName;
});
```

Use either a configured handler, events, or both. If both are enabled, the event is dispatched before the handler result is converted into the HTTP response.

## TelegramWebhookUpdate

`TelegramWebhookUpdate` keeps the raw payload and detects all Bot API 10.0 update fields:

```php
$update->updateId();          // 123456
$update->type();              // message, callback_query, guest_message, ...
$update->data();              // payload for the detected type
$update->has('message');      // bool
$update->get('message.text'); // nested access with dot notation
$update->payload();           // full raw update payload
```

For common Telegram objects, use typed accessors when you want IDE-friendly webhook code while keeping the raw payload available:

```php
$message = $update->effectiveMessage();
$chat = $update->effectiveChat();
$user = $update->effectiveUser();
$callbackQuery = $update->callbackQuery();
$inlineQuery = $update->inlineQuery();
$shippingQuery = $update->shippingQueryData();
$preCheckoutQuery = $update->preCheckoutQueryData();
$chatMember = $update->chatMember();
$poll = $update->poll();
$pollAnswer = $update->pollAnswer();
$reaction = $update->messageReaction();
$chatBoost = $update->chatBoost();
$businessConnection = $update->businessConnection();
$managedBot = $update->managedBot();

$message?->messageId();       // int|null
$message?->messageThreadId(); // int|null
$message?->text();            // string|null
$message?->caption();         // string|null
$message?->replyToMessage();  // TelegramMessageData|null
$message?->photoData();       // list<TelegramPhotoSizeData>
$message?->documentData();    // TelegramDocumentData|null
$message?->entitiesData();    // list<TelegramMessageEntityData>
$message?->successfulPaymentData(); // TelegramSuccessfulPaymentData|null
$chat?->id();                 // int|string|null
$chat?->type();               // private, group, supergroup, channel, ...
$user?->id();                 // int|string|null
$user?->username();           // string|null
$callbackQuery?->id();        // string|null
$callbackQuery?->data();      // string|null
$callbackQuery?->message();   // TelegramMessageData|null
$inlineQuery?->query();       // string|null
$shippingQuery?->invoicePayload(); // string|null
$preCheckoutQuery?->totalAmount(); // int|null
$preCheckoutQuery?->orderInfoData(); // TelegramOrderInfoData|null
$chatMember?->newChatMemberData(); // TelegramChatMemberData|null
$poll?->question();           // string|null
$pollAnswer?->optionIds();    // list<int>
$reaction?->newReaction();    // list<array<string, mixed>>
$chatBoost?->boostData();     // TelegramChatBoostData|null
$businessConnection?->isEnabled(); // bool|null
$managedBot?->bot();          // TelegramUserData|null
```

Direct message-like accessors are also available: `message()`, `editedMessage()`, `channelPost()`, `editedChannelPost()`, `businessMessage()`, `editedBusinessMessage()`, and `guestMessage()`.
Callback query updates are available through `callbackQuery()`, including typed `from()` and `message()` accessors.
Inline mode is covered by `inlineQuery()` and `chosenInlineResult()`.
Payment queries keep their backward-compatible array accessors and add typed `shippingQueryData()` and `preCheckoutQueryData()`.
Common message media, entities, successful payments, order info, and chat member payloads also have typed object accessors such as `photoData()`, `documentData()`, `entitiesData()`, `successfulPaymentData()`, `orderInfoData()`, and `newChatMemberData()`.
The remaining official update families are covered by `businessConnection()`, `deletedBusinessMessages()`, `purchasedPaidMediaData()`, `poll()`, `pollAnswer()`, `messageReaction()`, `messageReactionCount()`, `chatBoost()`, `removedChatBoost()`, and `managedBot()`.
Membership updates are available through `myChatMember()`, `chatMember()`, and `chatJoinRequest()`.

Unknown future update fields remain available through `payload()` and `get()` even before the SDK adds first-class awareness.

## Common Handler Patterns

Message command:

```php
$message = $update->effectiveMessage();

if ($message?->text() === '/start') {
    $this->telegram->bot($botName)->sendMessage([
        'chat_id' => (string) $message->chat()?->id(),
        'text' => 'Ready.',
    ]);
}
```

Callback button:

```php
$callback = $update->callbackQuery();

if ($callback?->data() === 'menu:settings') {
    $this->telegram->bot($botName)->answerCallbackQuery([
        'callback_query_id' => $callback->id(),
    ]);
}
```

Payment pre-checkout:

```php
$query = $update->preCheckoutQueryData();

if ($query !== null) {
    $this->telegram->bot($botName)->answerPreCheckoutQuery([
        'pre_checkout_query_id' => $query->id(),
        'ok' => true,
    ]);
}
```

Chat member update:

```php
$member = $update->chatMember();

if ($member?->newChatMemberData()?->status() === 'administrator') {
    // Grant app-side moderation permissions.
}
```

## Route And Middleware

The default route is registered by the service provider:

```text
POST /telegram-bot/webhook
```

Customize it in config:

```php
'route' => [
    'enabled' => true,
    'uri' => 'integrations/telegram/webhook',
    'name' => 'telegram.webhook',
    'middleware' => ['throttle:telegram-webhook'],
],
```

Package middleware always validates `X-Telegram-Bot-Api-Secret-Token` when `secret_token` is configured. It also fails closed when `require_secret` is true and the secret is missing. Add rate limiting, IP filtering, or observability middleware in the `middleware` array when the host application needs it.

The receiver rejects malformed updates before dispatching events or handlers. A valid incoming payload must be JSON and include an integer `update_id`, matching Telegram's `Update` object contract.

## Logging

When `telegram-bot.logging.enabled` is true, the Laravel integration writes warning/error logs for:

- rejected webhook secret tokens and missing required secret configuration;
- invalid webhook update payloads;
- invalid webhook handler configuration;
- webhook handler failures before the exception is rethrown;
- Telegram Bot API `ok: false` responses and transport response failures from Laravel-resolved bot clients.

Log context is intentionally limited to operational metadata such as method names, HTTP status codes, Telegram error codes, update IDs, update types, bot names, and exception classes. The package does not log bot tokens, webhook secret header values, request payloads, response bodies, chat IDs, or message text.

## Security Checklist

- Use HTTPS for public Telegram webhooks.
- Set `TELEGRAM_WEBHOOK_SECRET_TOKEN` and pass the same value to `setWebhook`.
- Keep `TELEGRAM_WEBHOOK_REQUIRE_SECRET=true` in production so a missing secret does not silently expose the route.
- Keep `TELEGRAM_BOT_LOGGING_ENABLED=true` unless the host application has equivalent monitoring.
- Do not commit real bot tokens, webhook secrets, chat IDs, logs, or payload dumps.
- Avoid long-running work in the webhook request; queue it.
- Use `allowed_updates` in `setWebhook` to reduce unnecessary traffic.
- Use `getWebhookInfo` after deployment to confirm Telegram sees the expected URL and has no delivery errors.
